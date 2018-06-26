<?php namespace spec\EventSourcery\EventSourcery\PhpSpec;

use EventSourcery\EventSourcery\EventSourcing\StreamId;
use spec\EventSourcery\EventSourcery\Commands\ArgumentCommandStub;
use spec\EventSourcery\EventSourcery\Commands\ResolutionTargetStub;
use spec\EventSourcery\EventSourcery\Commands\Stubs\TestCountingCommand;
use spec\EventSourcery\EventSourcery\EventSourcing\TestCountingEvent;

class TestingToolsSpec extends EventSourcedBehavior {

    function let() {
        $this->setup();
    }

    function it_can_be_tested() {
        $this->container->set(ResolutionTargetStub::class, new ResolutionTargetStub(5));
        $this->classMap->add('TestCountingEvent', TestCountingEvent::class);

        $this->given(
            StreamId::fromString('id'),
            new TestCountingEvent(1),
            new TestCountingEvent(2)
        )->when(
            new TestCountingCommand(3)
        )->then(
            new TestCountingEvent(3)
        );
    }
}

class Test {


}

/**

 public class AggregateSpec {

readonly CommandBus Bus;
readonly EventStream Events;
Command Command;

List<DomainEvent> ReceivedEvents { get; set; }

public AggregateSpec(CommandBus bus, EventStream events) {
    Bus = bus;
    Events = events;
    ReceivedEvents = new List<DomainEvent>();

            events.DomainEventReceived += ev => ReceivedEvents.Add(ev);
        }

        public AggregateSpec given() {
            return this;
        }

        public AggregateSpec given(Id streamId, params DomainEvent[] history) {
    Events.Publish(streamId, new List<DomainEvent>(history));
            return this;
        }

        public AggregateSpec when(Command command) {
    Command = command;
    return this;
}

        public void then(params DomainEvent[] domainEvents) {
    Bus.Handle(Command);

    foreach (var domainEvent in domainEvents) {
        var found = false;
        foreach (var receivedEvent in ReceivedEvents) {

            if (domainEvent.GetType().ToString().Equals(receivedEvent.GetType().ToString())) {
                if (JsonConvert.SerializeObject(domainEvent) == JsonConvert.SerializeObject(receivedEvent)) {
                    found = true;
                }
            }
        }

                if (! found) {
                    Assert.Fail("Could not find " + domainEvent.GetType().ToString() + " " + JsonConvert.SerializeObject(domainEvent) + " in received events.");
                }
            }
        }

        public void thenFail<T>() where T : Exception {
    Assert.Throws<T>(
    delegate { Bus.Handle(Command); } );

        }

    }
 **/