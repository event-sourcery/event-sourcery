<?php namespace spec\EventSourcery\Collections;

use EventSourcery\Collections\Collection;
use EventSourcery\EventSourcing\DomainEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use EventSourcery\Collections\TypedCollection;

class TypedCollectionSpec extends ObjectBehavior {

    function let() {
        $this->beAnInstanceOf(TestTypedCollection::class);
        $this->beConstructedThrough('make', [[
            new DomainEventStub(),
            new DomainEventStub()
        ]]);
    }

    function it_can_be_constructed_with_elements_of_the_correct_type() {
        $this->beConstructedThrough('make', [[
            new DomainEventStub(),
            new DomainEventStub()
        ]]);
        $this->shouldHaveType(TestTypedCollection::class);
    }

    function it_cannot_be_constructed_with_elements_of_another_type() {
        $this->beConstructedThrough('make', [[
            new DomainEventStub(),
            new \stdClass(),
        ]]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_can_add_a_value_of_the_correct_type() {
        $this->add(new DomainEventStub())->count()
            ->shouldBe(3);
        $this->add(new DomainEventStub())->count()
            ->shouldBe(3);
    }

    function it_cannot_add_a_value_of_another_type() {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->during('add', [new \stdClass()]);
    }

    function it_can_map_to_a_typed_collection() {
        $this->map(function($i) { return $i; })
            ->shouldHaveType(TestTypedCollection::class);
    }

    function it_can_fall_back_to_generic_collections_when_mapping_to_other_types() {
        $this->map(function($i) { return 1; })
            ->shouldHaveType(Collection::class);
    }
}

class TestTypedCollection extends TypedCollection {
    protected $collectionType = DomainEvent::class;
}