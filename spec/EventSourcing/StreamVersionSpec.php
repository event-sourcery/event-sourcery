<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\StreamVersion;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StreamVersionSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedThrough('zero');
    }

    function it_can_be_compared_with_other_stream_versions() {
        $this->beConstructedThrough('fromInt', [33]);
        $this->shouldEqualValue(StreamVersion::fromInt("33"));
    }

    function it_starts_at_zero() {
        $this->shouldEqualValue(StreamVersion::zero());
    }

    function it_can_returns_an_int() {
        $this->beConstructedThrough('fromInt', [12]);

        $this->toInt()->shouldBeInt();
        $this->toInt()->shouldReturn(12);
    }

    function it_can_provide_the_next_version() {
        $this->beConstructedThrough('fromInt', [23]);
        $this->next()->shouldEqualValue(StreamVersion::fromInt(24));
    }
}
