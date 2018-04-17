<?php namespace spec\EventSourcery\EventSourcing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use function EventSourcery\PhpSpec\expect;
use EventSourcery\EventSourcing\CannotCompareDifferentIds;

class IdSpec extends ObjectBehavior {

    function let() {
        $this->beAnInstanceOf(TestId::class);
        $this->beConstructedThrough('fromString', ['anId']);
    }

    function it_contains_an_id_string() {
        $this->toString()->shouldReturn('anId');
    }

    function it_can_be_cast_to_string() {
        expect((string) $this->getWrappedObject())->toBe('anId');
    }

    function it_compares_ids_by_value() {
        $this->shouldEqualValue(TestId::fromString('anId'));
    }

    function it_compares_ids_of_the_same_type_only() {
        $this->shouldThrow(CannotCompareDifferentIds::class)
            ->during('equals', [TestIdTwo::fromString('anId')]);
    }

    function it_can_generate_new_ids() {
        $this->beConstructedThrough('generate');
        $this->shouldNotEqualValue(TestId::generate());
    }
}