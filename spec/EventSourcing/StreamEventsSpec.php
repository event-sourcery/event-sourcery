<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\StreamEvent;
use EventSourcery\EventSourcing\StreamId;
use EventSourcery\EventSourcing\StreamVersion;
use PhpSpec\ObjectBehavior;
use spec\EventSourcery\Collections\DomainEventStub;

class StreamEventsSpec extends ObjectBehavior {

    function it_can_hold_stream_events() {
        $this->beConstructedThrough('make', [[
            new StreamEvent(StreamId::fromString("id"), StreamVersion::zero(), new DomainEventStub()),
            new StreamEvent(StreamId::fromString("id2"), StreamVersion::zero(), new DomainEventStub())
        ]]);
        $this->count()->shouldBe(2);
    }

    function it_cant_hold_anything_else() {
        $this->beConstructedThrough('make', [[new DomainEventStub()]]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_can_return_a_collection_of_domain_events() {
        $this->beConstructedThrough('make', [[
            new StreamEvent(StreamId::fromString("id"), StreamVersion::zero(), new DomainEventStub(1))
        ]]);
        $this->toDomainEvents()->shouldContainEvent(new DomainEventStub(1));
    }
}
