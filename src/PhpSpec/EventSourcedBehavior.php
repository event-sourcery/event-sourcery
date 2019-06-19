<?php namespace EventSourcery\EventSourcery\PhpSpec;

use EventSourcery\EventSourcery\Commands\Command;
use EventSourcery\EventSourcery\Commands\CommandBus;
use EventSourcery\EventSourcery\Commands\ReflectionResolutionCommandBus;
use EventSourcery\EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcery\EventSourcing\DomainEventClassMap;
use EventSourcery\EventSourcery\EventSourcing\EventStore;
use EventSourcery\EventSourcery\EventSourcing\StreamEvent;
use EventSourcery\EventSourcery\EventSourcing\StreamEvents;
use EventSourcery\EventSourcery\EventSourcing\StreamId;
use EventSourcery\EventSourcery\EventSourcing\StreamVersion;
use EventSourcery\EventSourcery\Serialization\DomainEventSerializer;
use EventSourcery\EventSourcery\Serialization\ReflectionBasedDomainEventSerializer;
use EventSourcery\EventSourcery\Serialization\ValueSerializer;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use spec\EventSourcery\EventSourcery\Commands\ContainerStub;
use spec\EventSourcery\EventSourcery\EventSourcing\TestEventStoreSpy;
use spec\EventSourcery\EventSourcery\PersonalData\PersonalDataStoreStub;

class EventSourcedBehavior extends ObjectBehavior {

    /**
     * collection of stream events that represent the environment
     * that the command is being run within
     *
     * @var StreamEvents
     */
    private $environment;

    /**
     * the command bus implementation that we'll be using for
     * testing
     *
     * @var CommandBus
     */
    private $commandBus;

    /**
     * the event store implementation that we'll be using for
     * testing
     *
     * @var EventStore
     */
    private $eventStore;

    /**
     * the domain event serializer implementation that we'll
     * use to measure event equality
     *
     * @var DomainEventSerializer
     */
    private $serializer;

    /**
     * the container implementation that we use to resolve
     * from.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * the class map where you can bind domain events to
     * classes in the system for serialization
     *
     * @var DomainEventClassMap
     */
    protected $classMap;

    /**
     * the command to execute upon trigger of a then() method
     *
     * @var Command
     */
    private $commandToExecute;

    /**
     * setup() is the initialization function that will
     * define our dependencies. call it from the objectbehavior's
     * let() function
     *
     * ```php
     *     function let() {
     *         $this->setup();
     *     }
     * ```
     */
    function setup() {
        $dataStore = new PersonalDataStoreStub();
        $this->eventStore = new TestEventStoreSpy();

        $this->container = new ContainerStub();
        $this->container->set(EventStore::class, $this->eventStore);

        $this->classMap  = new DomainEventClassMap();
        $this->commandBus = new ReflectionResolutionCommandBus($this->container);

        $this->serializer = new ReflectionBasedDomainEventSerializer(
            $this->classMap,
            new ValueSerializer($dataStore),
            $dataStore
        );
    }

    /**
     * given() is the method to use to set up an environment
     * that consists of a single event stream.
     *
     * the environment is the collection of events that need to
     * already exist within the event store in order to test that
     * the command results in the generation of the correct outcome
     *
     * the first argument is the stream id, the rest of the arguments
     * are a variadic list of domain events.
     *
     * ```php
     *     $this->given(
     *         StreamId::fromString('test id'),
     *         new ExampleEvent(1),
     *         new ExampleEvent(1),
     *     )->when(
     *         // ...
     *     )->then(
     *         // ...
     *     );
     * ```
     *
     * @param StreamId $id
     * @param DomainEvent ...$domainEvents
     * @return $this
     */
    public function given(StreamId $id, DomainEvent ...$domainEvents) {
        $version = StreamVersion::zero();

        $this->environment = StreamEvents::make(
            array_map(function (DomainEvent $event) use ($id, &$version) {
                $streamEvent = new StreamEvent($id, $version, $event);
                $version = $version->next();
                return $streamEvent;
            }, $domainEvents)
        );

        return $this;
    }

    /**
     * givenStreams() is the method to use to set up an environment
     * that consists of multiple event streams.
     *
     * the environment is the collection of events that need to
     * already exist within the event store in order to test that
     * the command results in the generation of the correct outcome
     *
     * the argument is a variadic list of StreamEvents collections.
     *
     * ```php
     *     $this->givenStreams(
     *         $stream1,
     *         $stream2,
     *         $stream3,
     *     )->when(
     *         // ...
     *     )->then(
     *         // ...
     *     );
     * ```
     *
     * @param StreamEvents[] $streamEvents
     * @return $this
     */
    public function givenStreams(StreamEvents ...$streamEvents) {
        $arraysOfEvents[] = array_map(function (StreamEvents $streamEvents) {
            return $streamEvents->toArray();
        }, $streamEvents);

        $this->environment = StreamEvents::make(array_merge(...$arraysOfEvents));

        return $this;
    }

    /**
     * when() is the method with which you define the command
     * that will be fired into the system in order to produce the
     * state change that you're testing.
     *
     * ```php
     *     $this->given(
     *         // ...
     *     )->when(
     *         new ExampleCommand('arg1', 2, 'arg3')
     *     )->then(
     *         // ...
     *     );
     * ```
     *
     * @param Command $command
     * @return $this
     */
    public function when(Command $command) {
        $this->commandToExecute = $command;
        return $this;
    }

    /**
     * then() is the method that triggers the execution of the
     * command and then validates that the Domain Events passed
     * as the argument exist within the environment.
     *
     * ```php
     *     $this->given(
     *         // ...
     *     )->when(
     *         // ...
     *     )->then(
     *         new ExampleEvent(1),
     *         new ExampleEvent(1)
     *     );
     * ```
     *
     * @param DomainEvent ...$events
     * @throws \Exception
     */
    public function then(DomainEvent ...$events) {
        if ( ! $this->commandBus) {
            throw new FailureException('Cannot execute specification without calling beInitializedWith().');
        }
        $this->commandBus->execute($this->commandToExecute);
        $this->eventsShouldExistInStore(...$events);
    }

    /**
     * thenFail() is the method to use when you want to verify
     * that given the existing environment, the command will
     * result in an exception.
     *
     * ```php
     *     $this->given(
     *         // ...
     *     )->when(
     *         // ...
     *     )->thenFail(
     *         new SomethingWentWrong(...)
     *     );
     * ```
     *
     * @param \Exception $expected
     * @throws FailureException
     */
    public function thenFail(\Exception $expected) {
        try {
            $this->commandBus->execute($this->commandToExecute);
            $expectedType = get_class($expected);
            throw new FailureException("Expected exception {$expectedType} but it was not thrown.");
        } catch (\Exception $caught) {
            $expectedType = get_class($expected);
            $caughtType   = get_class($caught);

            if (
                $caughtType != $expectedType ||
                $caught->getMessage() != $expected->getMessage()
            ) {

                throw new FailureException("Expected exception {$expectedType} {$expected->getMessage()} but caught {$caughtType} {$caught->getMessage()} instead.");
            }
        }
    }

    /**
     * internal method that throws an exception should the expected
     * events not exist within the event store
     *
     * @param DomainEvent ...$events
     * @throws FailureException
     */
    private function eventsShouldExistInStore(DomainEvent ...$events) {
        $expected = array_map(function (DomainEvent $event) {
            return $this->serializer->serialize($event);
        }, $events);

        $existing = $this->eventStore->getEvents()->map(function(DomainEvent $event) {
            return $this->serializer->serialize($event);
        })->toArray();

        $notFound = array_filter($expected, function($expectedEvent) use ($existing) {
            return ! in_array($expectedEvent, $existing);
        });

        if (empty($notFound)) return;

        throw new FailureException($this->missingEventExceptionMessage($notFound));
    }

    private function missingEventExceptionMessage(array $notFound) {
        $events = array_map(function($arr) {
            $event = json_decode($arr);
            $eventName = $event->eventName;
            $eventFields = json_encode($event->fields);

            return "{$eventName} {$eventFields}";
        }, $notFound);

        $list = implode(', ', $events);

        return "Expected but did not receive the following events {$list}";
    }
}