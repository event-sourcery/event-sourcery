<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\StreamId;
use EventSourcery\EventSourcing\StreamVersion;
use PhpSpec\ObjectBehavior;
use spec\EventSourcery\Collections\DomainEventStub;

class StreamEventSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->beConstructedWith(StreamId::fromString('id'), StreamVersion::fromInt(1), new DomainEventStub());
        $this->id()->shouldEqualValue(StreamId::fromString('id'));
        $this->event()->shouldHaveType(DomainEventStub::class);
        $this->version()->shouldEqualValue(StreamVersion::fromInt(1));
    }
}
