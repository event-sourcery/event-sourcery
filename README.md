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

There are a few requirements for the default implementation of the domain event serializer.

1. It only handles strings, ints, bools, and any type that inherits from `SerializableValue`. 
2. All values must be injected into the constructor and their fields should be assigned to fields with the same name as seen in this example:

```PHP
class ValueObjectEventStub implements DomainEvent {

    /** @var ValueObject */
    public $vo;

    public function __construct(ValueObject $vo) {
        $this->vo = $vo;
    }
}
```

> Notice how `$vo` is the constructor argument and it assigns to the field of the same name `$vo`.

So long as these requirements are met then the default serializer will be able to handle them effortlessly. It makes no discrimination as to whether you use private fields and getter methods or public fields.

## Commands and Handlers ##

@todo

## Event Store ##

@todo

## Event Dispatching and Listeners ##

@todo

## Process Managers ##

@todo