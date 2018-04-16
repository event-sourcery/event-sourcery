<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\StreamEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EventSourcery\EventSourcing\StreamVersion;

class AggregateSpec extends ObjectBehavior {

    function let() {
        $this->beAnInstanceOf(TestAggregate::class);
        $this->beConstructedThrough('create');
    }

    function it_can_raise_a_domain_event() {
        $this->raiseEvent(new TestCountingEvent);
        $events = $this->flushEvents();
        $events->shouldHaveType(StreamEvents::class);
        $events->count()->shouldBe(1);
    }

    function it_can_raise_domain_events() {
        $this->raiseEvent(new TestCountingEvent);
        $this->raiseEvent(new TestCountingEvent);

        $events = $this->flushEvents();
        $events->shouldHaveType(StreamEvents::class);
        $events->count()->shouldBe(2);
    }

    function it_wont_release_the_same_events_twice() {
        $this->raiseEvent(new TestCountingEvent);
        $this->raiseEvent(new TestCountingEvent);

        $this->flushEvents();
        $events = $this->flushEvents();
        $events->shouldHaveType(StreamEvents::class);
        $events->count()->shouldBe(0);
    }

    function it_can_play_back_events() {
        $this->raiseEvent(new TestCountingEvent(5));
        $this->appliedEventCount()->shouldBe(5);
        $this->raiseEvent(new TestCountingEvent(2));
        $this->appliedEventCount()->shouldBe(7);
    }

    function it_increments_the_stream_version_when_applying_events() {
        $this->aggregateVersion()->shouldEqualValue(StreamVersion::zero());
        $this->raiseEvent(new TestCountingEvent(5));
        $this->raiseEvent(new TestCountingEvent(7));
        $this->aggregateVersion()->shouldEqualValue(StreamVersion::fromInt("2"));
    }
}