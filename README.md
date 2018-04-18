# Event Sourcery PHP CQRS / ES Library #

The Event Sourcing / CQRS framework whose core principle is keeping it simple.

# Components #

## Values ##

Values represent things like names, temperature, ids, etc. Anything that uses value semantics derives from the following classes.

> Value semantics: equality is determined by comparing value, not ID. 

1. `SerializableValue`
2. `SerializablePersonalDetails`

These contracts require that you implement `toString()` and `fromString()` methods that will be used during domain event serialization.

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

@todo

## Event Dispatching and Listeners ##

@todo

## Process Managers ##

@todo

# General Data Protection Regulation Compliance

Read up on [GDPR Compliance Requirements](https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-erasure/) before continuing to develop your application.

GDPR must be considered at all times while developing. It's not something that can be implemented in an automatic way. However, this library seeks to provide quality-of-life features in regard to GDPR that should be able to make your life easier.

With this framework, GDPR is implemented with encryption and a personal key store.

## Personal Key Store ##

The personal key store stores `personal keys`, `encryption keys`, and `metadata`.

### Personal Keys ###

A personal key is a unique key that identifies a single **person**. A **person** is defined as an individual who is having personal identifying data stored that will need to be forgotten if they invoke the `right to erasure`.

A human being may be represented as multiple **person**s in our system. For example, imagine a human signs up for a mailing list that contains fields that we are identifying as personal data. Then this same human registers for another service in our system. We may not have the necessary information or the desire to identify them as the same person. In this case, a human is represented as two **person**s.

On the entry-point into the application, a **person** is assigned a personal identification token or `PIT`. This token is stored with the personal data and will never be erased from our system.

A `PIT` is first generated by the `Personal Key Store`. 

### Encryption Keys ###

When a `PIT` is first generated An `encryption key` is assigned to the `PIT`. The `Personal Key Store` can be queried to get the `encryption key`. The `encryption key` should never be stored and should always instead be queried from the `Personal Key Store`.

The `encryption key` is used for decrypting private personal data.

## Invoking the Right to Erasure ##

When invoking the `Right to Erasure` the `Personal Key Store` simply destroys the encryption key.

## Right to Erasure and Projections ##

@todo