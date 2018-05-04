<?php namespace spec\EventSourcery\EventSourcery\EventSourcing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use EventSourcery\EventSourcery\EventSourcing\CannotCompareDifferentIds;

class IdSpec extends ObjectBehavior {

    function let() {
        $this->beAnInstanceOf(TestId::class);
        $this->beConstructedThrough('fromString', ['anId']);
    }

    function it_contains_an_id_string() {
        $this->toString()->shouldReturn('anId');
    }

    function it_compares_ids_by_value() {
        $this->shouldEqualValue(TestId::fromString('anId'));
    }

    function it_compares_ids_of_the_same_type_only() {
        $this->shouldThrow(CannotCompareDifferentIds::class)
            ->during('equals', [TestIdTwo::fromString('anId')]);
    }
}