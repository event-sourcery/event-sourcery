<?php namespace spec\EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\EventSourcing\StreamId;
use PhpSpec\ObjectBehavior;

class StreamIdSpec extends ObjectBehavior {
    public function it_is_initializable() {
        $this->shouldHaveType(StreamId::class);
    }
}