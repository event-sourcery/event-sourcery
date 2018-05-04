<?php namespace spec\EventSourcery\EventSourcery\EventSourcing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EventSourcery\EventSourcery\EventSourcing\CannotCompareDifferentIds;

class CannotCompareDifferentIdsSpec extends ObjectBehavior {
    function it_is_initializable() {
        $this->shouldHaveType(CannotCompareDifferentIds::class);
    }
}
