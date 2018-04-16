<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\StreamEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EventSourcery\EventSourcing\StreamId;
use EventSourcery\EventSourcing\StreamVersion;

class StreamEventsSpec extends ObjectBehavior {

    function it_can_hold_stream_events() {
        $this->beConstructedThrough('make', [[
            new StreamEvent(StreamId::fromString("id"), StreamVersion::zero(), new TestDomainEvent()),
            new StreamEvent(StreamId::fromString("id2"), StreamVersion::zero(), new TestDomainEvent())
        ]]);
        $this->count()->shouldBe(2);
    }

    function it_cant_hold_anything_else() {
        $this->beConstructedThrough('make', [[new TestDomainEvent()]]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_can_return_a_collection_of_domain_events() {
        $this->beConstructedThrough('make', [[
            new StreamEvent(StreamId::fromString("id"), StreamVersion::zero(), new TestDomainEvent(1))
        ]]);
        $this->toDomainEvents()->shouldContainEvent(new TestDomainEvent(1));
    }
}
