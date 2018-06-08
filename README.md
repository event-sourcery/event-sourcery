# Event Sourcery PHP CQRS / ES Library #

The Event Sourcing / CQRS framework whose core principle is keeping it simple.

**Library is under conceptual development. Do not use.**

This library is highly volatile and will change rapidly. It's being implemented into multiple production projects and will stabilize more soon.

Much of the implementation has been spiked and some of it is even untested. By release 1.0 the entire thing will be re-cut with lessons learned from using it in its current form.

# Table of Contents

* [Core Values / Concepts](#values)
* [Todo List](#todo-list)
* [Installation](#installation)
* [Components](#components)
    * [Values](#values-1)
        * [Serializable Value](#serializable-value)
        * [Serializable Personal Data Value](#serializable-personal-data-value)
        * [Serializable Personal Data Value](#serializable-personal-data-value)
    * [Entities](#entities)
        * [Entity IDs](#entity-ids)
    * [Aggregates](#aggregates)
    * [Domain Events](#domain-events)
        * [Serialization](#serialization)
    * [Commands](#commands)
    * [Event Store](#event-store)
    * [Event Dispatching and Listeners](#event-dispatching-and-listeners)
    * [Process Managers / Sagas](#process-managers)
    * [Projections](#projections)
* [Personal Data Security](#personal-data-security)
    * [Personal Keys](#personal-keys)
    * [Personal Data Store](#personal-data-store)
    * [Personal Cryptography Store](#personal-cryptography-store)
    * [Best Practices for Personal Data and Encryption Key Stores](#best-practices-for-personal-data-and-encryption-key-stores)
    * [Driver Implementations](#driver-implementations)
        * [Encryption Keys](#encryption-keys)
* [Notes](#notes)

# Values #

The core values / concepts are:

1. Reduce the amount of moving pieces that developers need to write / test.
2. Implement with simple idiomatic code that can easily be analyzed by your favorite IDE. 
3. No custom event serialization. Push any serialization to value objects so that you implement and test serialiation once and not with every event you create. 
4. Optional personal data value objects store into a separate data store. The event stays within the typical event store. Easily remove all personal data for an individual who invokes the 'right to erasure'.  

Examples of working with personal data can be found in [PERSONAL_DATA_EXAMPLE.md](https://github.com/event-sourcery/event-sourcery/blob/master/PERSONAL_DATA_EXAMPLE.md).

# Todo List #

1. Switch to libsodium / better encryption model
2. All commands are logged.
3. Commands / events get unique IDs.
4. All the other stuff
5. Key management systems?

# Installation #

`composer require event-sourcery/event-sourcery`

# Components #

## Values ##

Values represent things like names, temperature, ids, etc. Anything that uses value semantics derives from the following classes.

> Value semantics: equality is determined by comparing value, not ID. 

1. `SerializableValue`
2. `SerializablePersonalDataValue`

The `SerializableValue` contract requires that you implement `serialize()` and `deserialize()` methods that will be used during domain event serialization.

The `PersonalData` contract tags the value as containing secure personal data that complies with our data protection policies. (is encrypted on persistence and can be erased in compliance with GDPR)  

### Serializable Value ###

The `Serialization\SerializableValue` interface is used to provide to/from string serialization to value objects. This interface is used by the automatic domain-event and command serializer.

Any value that is of type `string`, `int`, `bool`, or extends `SerializableValue` will be able to be automatically stored and retrieved without custom event/command serialization code in userland.

This decision was made because it's easier to write / test the serialization of a value once than it is to test values N times where N is the number of events or commands that have to be written and individually tested. 

```php
<?php
class PersonsName implements SerializableValue {

    /** @var string */
    public $firstName;
    /** @var string */
    public $lastName;

    public function __construct(string $firstName, string $lastName) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    // Implemented from SerializableValue 
    public function serialize(): string {
        return json_encode([
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ]);
    }

    // Implemented from SerializableValue
    public static function deserialize($string) {
        $values = json_decode($string);
        return new static($values->firstName, $values->lastName);
    }
}
```

### Serializable Personal Data Value ###

We can flag this value object as containing personal data by implementing the `SerializablePersonalDataValue` interface.

```php
<?php
class Email implements SerializablePersonalDataValue {
    
    /** @var PersonalKey */
    private $personalKey;        
    /** @var string */
    private $address;

    private function __construct(PersonalKey $personalKey, string $address) {
        $this->personalKey = $personalKey;
        $this->address = $address;
        
        if ( ! filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new EmailAddressIsNotValid($address);
        }
    }
    
    public static function fromString(PersonalKey $personalKey, string $address) {
        return new static($personalKey, $address);
    }
    
    public function toString() {
        if ($this->wasErased()) {
            throw new CannotRetrieveErasedPersonalData();
        }
        return $this->address;
    }

    // for serialization
    public function serialize(): string {
        return json_encode([
            'personalKey' => $this->personalKey->toString(),
            'address' => $this->address,
        ]);
    }

    public static function deserialize(string $string) {
        $values = json_decode($string);
        return new static(PersonalKey::fromString($values->personalKey), $values->address);
    }
    
    // for supporting erasable data    
    public function personalKey(): PersonalKey {
        return $this->personalKey;
    }
    
    public static function fromErasedState(PersonalKey $personalKey) {
        $email = static($personalKey, "erased-account@mycompany.com");
        $email->erased = true;
        return $email;
    }
    
    public function wasErased() {
        return $this->erased;
    }
    
    private $erased = false;
}
```

Now, our serializer will know to store the data in the personal data store and reference that datum by the `PersonalDataKey`. When deserializing, it'll see that the field is an encrypted value, it'll then trigger the process to collect the encrypted data from the store, then it will deserialize the object.

## Entities ##

Entities are objects that are represented with identifier equality. Two objects are equal if they have the same ID. If the values of two objects are identical however they have different IDs then they will not compare as equal. If the values are completely different but they share the same ID then they will compare as equal.

### Entity IDs ###

Identification is the process of determining one entity from others. In this framework, IDs are subclasses of one of the following classes:

1. Id
2. StreamId

The `Id` class exists to provide a basic Id implementation.

The `StreamId` class is a subclass as `Id`. It exists to differentiate stream IDs from any other kind of ID. Essentially, it only exists to make the code more expressive / easier to understand.

## Aggregates ##

Aggregates are singleton processes. Functionally only one can exist at a time. This is accomplished through ensuring that every aggregate update is given a sequence number and by using RDBMS transactions to prevent multiple processes from making a change to an aggregate at the same time.

The singleton nature of an aggregate allows for immediate consistency. Updates made to the aggregate (assuming no business rules were violated and the transactionality is upheld) can be immediately confirmed.

Aggregates provide public methods that trigger state changes. These public methods will guard business rules using internal state and then will update the aggregate by raising new events. The internal state that is used to guard business rules generally comes from events that have already occurred within that aggregate. Whenever an event occurs within an aggregate, its local state is updated, and that local state will be used for guarding future business rules.

## Domain Events ##

Domain events implement the `DomainEvent` interface. The interface is empty of public methods. It is used only as a marker to discriminate domain events. 

### Serialization ###

There are a few requirements for the included reflection-based domain event serializer.

1. It only handles strings, ints, bools, and any type that inherits from `SerializableValue`. 
2. All values must be injected into the constructor and their fields should be assigned to fields with the same name as seen in this example:

```php
<?php
class ValueObjectEventStub implements DomainEvent {

    /** @var ValueObject */
    public $vo;

    public function __construct(ValueObject $vo) {
        $this->vo = $vo;
    }
}
```

> Notice how `$vo` is the constructor argument and it assigns to the field of the same name `$vo`.

So long as these requirements are met then the provided reflection-based serializer will be able to handle them effortlessly. It makes no discrimination as to whether you use private fields and getter methods or public fields.

## Commands ##

Commands represent the "C" in CQRS. They are triggered behavior that results in some finite system state change or external side-effects. You choose.

This example illustrates the idiom of the framework:

```php
<?php
class RegisterCandidate implements Command {

    /** @var VoucherId */
    private $voucherId;
    /** @var CandidateName */
    private $candidateName;
    /** @var Email */
    private $candidateEmail;
    /** @var Timestamp */
    private $registerAt;

    public function __construct(string $voucherId, string $candidateName, string $candidateEmail, string $registerAt) {
        $this->voucherId = VoucherId::fromString($voucherId);
        $this->candidateName = CandidateName::fromString($candidateName);
        $this->candidateEmail = Email::fromString($candidateEmail);
        $this->registerAt = Timestamp::fromString($registerAt);
    }

    public function execute(EventStore $events) {
        $candidate = Candidate::buildFrom($events->getStream($this->voucherId));
        $candidate->doSomething();
        $events->storeStream($candidate->flushEvents());
    }
}
```

This example contains two methods; the constructor and the execute(). 

The `constructor` is used as a translation layer from primitives (collected in the UI) into domain objects.

The `execute` method actual implements the behavior of the command. No arguments are necessary in the execute() method. However if you type-hint arguments, the framework will resolve those arguments from a [PSR-11 compliant](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) container in order to resolve and automatically inject the dependencies into the `execute` method.

The `execute` method is called by the command bus.

Here is an example controller method:

```php
<?php
class ExampleController {

    public function __construct(CommandBus $bus) {
        $this->bus = $bus;
    } 

    public function controllerMethod(Request $request) {
        $this->bus->execute(new RegisterCandidate(
            $request->get('voucherId'),
            $request->get('candidateName'),
            $request->get('candidateEmail'),
            "2017-01-02 03:04:05"
        ));
    }
}
```

The command bus' job is to provide any amount of functionality as well as calling the `execute()` method. The provided implementation uses a [PSR-11 compliant service container](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md).

## Event Store ##

The event store's job is to store and query back events. The core of our implementation is the `EventStore` interface. From there you can implement the store in any way you like.

There's probably a provided RDBMS implementation. The provided implementation stores the event, then dispatches the event via the event dispatcher.

## Event Dispatching and Listeners ##

Event dispatching is the process of delivering newly stored events to event listeners.

Event listeners are any kind of component that react to newly dispatched events.

Behaviors like aggregate event replay do not trigger event listeners because these events were already delivered to listeners once when they were first stored. Listeners won't receive the same event more than once.

## Process Managers ##

Process managers are considered a special type of event listener because they have the potential to directly mutate the state of the system through raising events or executing commands that result in new events.

## Projections ##

Projections are an interpretation of event streams. 

# Personal Data Security #

Personal data security is a core value of this library. However, personal security is not something that can be implemented in an automatic way.

We believe that we have a deep responsibility to our fellow people to serve their rights first and foremost over economic gain. We are building this library in a way that seeks to maximize our capability to respect the sanctity of their rights for privacy.  

In order to best protect personal privacy we use the following conventions:

1. No personal data is stored in the event store. Instead, it's stored in a `personal data store`.
2. No personal data is store unencrypted. The data in the personal data store are encrypted with keys that exist within a separate `personal cryptography store`.
3. All systems that utilize personal data must be built with the expectation that the personal data may need to be erased in compliance with GDPR and that the system / application must remain functional.
4. We encourage compliance with GDPR even for developers who are operating outside of the European Union.  

We believe in the upholding the values of the [Data Protection Act](https://ico.org.uk/for-organisations/guide-to-data-protection/) and [General Data Protection Regulation Compliance](https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/).

## Personal Keys ##

A personal key is a unique key that identifies a single **person**. A **person** is defined as an individual who is having personal identifying data stored that will need to be forgotten if they invoke the `right to erasure`.

Typically a Personal Key would be built out of an Id ```PersonalKey::fromId($memberId)```. Personal Keys are stored alongside personal data so that we can determine to whom personal data belongs. 

## Personal Data Store ##

The personal data store is the sole location in the application that holds personal data. This store can be implemented in any number of ways. Your implementation must implement the `PersonalDataStore` interface.

## Personal Cryptography Store ##

In our implementations, all personal data is encrypted at all times. Encryption keys are stored in a separate store called the `Personal Cryptography Store`. This store can be implemented in any number of ways. Your implementation must implement the `PersonalCryptographyStore` interface.

## Best Practices for Personal Data and Encryption Key Stores ##

It's recommended to ensure that should someone gain access to one of the three of the following, they will not be able to access personal data. 

1. Contents of your `Personal Data Store`.
2. Contents of your `Personal Cryptography Store`.
3. Your source code.

These tips can help ensure that this is the case:

1. Do not store addresses to or authentication information for your Personal Data Store or Personal Cryptography Store in source code.
2. Ensure that your stores are on systems isolated from your source code and each other.
3. Ensure that your stores are securely linked to your source code (or any systems that require access) through encrypted channels. At no point should holes be poked into your firewall so that systems can access your stores.

## Driver Implementations ##

Implementations of the stores will be provided as drivers. 

* [Laravel Driver](https://github.com/event-sourcery/laravel-event-sourcery-driver)
* Symfony Driver
* Stand-alone Driver  

When using a driver, composer require the driver rather than this package. 

### Encryption Keys ###

The first time that Personal Data is stored for `PersonalKey`, `cryptographic details` are assigned to the `Personal Key`. The `Personal Cryptography Store` can be queried to get the `cryptographic details`. The `cryptographic details` should never be cached and should always instead be queried from the `Personal Cryptography Store`.

The `cryptographic details` are used for decrypting private personal data.

# Notes #

1. Serialize() and Deserialize() are different from toString() and fromString(). The former methods are used SPECIFICALLY for serialization. The latter are used for utilizing strings as implementation or for outputting (for example to read models or exceptions) and individual implementations may require different.
