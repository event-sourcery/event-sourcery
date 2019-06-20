<?php namespace spec\EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\EventSourcing\CanNotBuildAggregate;
use EventSourcery\EventSourcery\EventSourcing\StreamEvents;
use EventSourcery\EventSourcery\EventSourcing\StreamVersion;
use PhpSpec\ObjectBehavior;

class MagicAggregateSpec extends ObjectBehavior
{

    function let()
    {
        $this->beAnInstanceOf(TestMagicAggregate::class);
        $this->beConstructedThrough('create');
    }

    function it_can_raise_a_domain_event()
    {
        $this->raiseEvent(new TestCountingEvent);
        $events = $this->flushEvents();
        $events->shouldHaveType(StreamEvents::class);
        $events->count()->shouldBe(1);
    }

    function it_can_raise_domain_events()
    {
        $this->raiseEvent(new TestCountingEvent);
        $this->raiseEvent(new TestCountingEvent);

        $events = $this->flushEvents();
        $events->shouldHaveType(StreamEvents::class);
        $events->count()->shouldBe(2);
    }

    function it_wont_release_the_same_events_twice()
    {
        $this->raiseEvent(new TestCountingEvent);
        $this->raiseEvent(new TestCountingEvent);

        $this->flushEvents();
        $events = $this->flushEvents();
        $events->shouldHaveType(StreamEvents::class);
        $events->count()->shouldBe(0);
    }

    function it_can_play_back_events()
    {
        $this->raiseEvent(new TestCountingEvent(5));
        $this->appliedEventCount()->shouldBe(5);
        $this->raiseEvent(new TestCountingEvent(2));
        $this->appliedEventCount()->shouldBe(7);
    }

    function it_increments_the_stream_version_when_applying_events()
    {
        $this->aggregateVersion()->shouldEqualValue(StreamVersion::zero());
        $this->raiseEvent(new TestCountingEvent(5));
        $this->raiseEvent(new TestCountingEvent(7));
        $this->aggregateVersion()->shouldEqualValue(StreamVersion::fromInt("2"));
    }

    function it_can_optionally_only_build_from_non_empty_event_streams()
    {
        $this->beConstructedThrough('buildFromOrFail', [StreamEvents::make([])]);

        $this->shouldThrow(CanNotBuildAggregate::class)->duringInstantiation();
    }
}