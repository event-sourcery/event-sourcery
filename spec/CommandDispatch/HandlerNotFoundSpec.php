<?php namespace spec\EventSourcery\CommandDispatch;

use EventSourcery\CommandDispatch\HandlerNotFound;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerNotFoundSpec extends ObjectBehavior {
    function it_is_initializable() {
        $this->shouldHaveType(HandlerNotFound::class);
    }
}
