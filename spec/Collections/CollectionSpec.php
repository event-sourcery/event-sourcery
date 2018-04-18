<?php namespace spec\EventSourcery\Collections;

use EventSourcery\Collections\Collection;
use PhpSpec\ObjectBehavior;
use function EventSourcery\PhpSpec\expect;

class CollectionSpec extends ObjectBehavior {

    private $fixture;

    function let() {
        $this->fixture = (object) [
            'set'         => [1, 2, 3, 4],
            'doubledSet'  => [2, 4, 6, 8],
            'filteredSet' => [0 => 1, 2 => 3],
            'count'       => 4,
            'sum'         => 10,
        ];

        $this->beConstructedThrough('make', [$this->fixture->set]);
    }

    function it_can_count_contained_items() {
        $this->count()->shouldBe($this->fixture->count);
    }

    function it_returns_new_collections_with_added_items() {
        $this->add(5)->shouldHaveCount($this->fixture->count + 1);
    }

    function it_can_call_a_function_against_each_item() {
        $this->each(function ($item) use (&$i) {
            $i++;
        });
        expect($i)->shouldBe($this->fixture->count);
    }

    function it_can_compare_itself_against_other_collections() {
        $this->shouldEqualValue(Collection::make($this->fixture->set));
    }

    function it_can_transform_the_set_using_a_function() {
        $this->map(function ($item) {
            return $item * 2;
        })
            ->shouldEqualValue(Collection::make($this->fixture->doubledSet));
    }

    function it_can_return_the_first_element() {
        $this->first()->shouldBe(1);
    }

    function it_can_be_serialized_to_an_array() {
        $this->toArray()->shouldBeArray();
        foreach ($this->fixture->set as $item) {
            $this->toArray()->shouldContain($item);
        }
    }

    function it_can_be_iterated_over() {
        $col = Collection::make($this->fixture->set);
        foreach ($col as $item) {
            expect($col->toArray())->shouldContain($item);
        }
    }

    function it_can_be_copied() {
        $this->shouldEqual($this);
        $this->copy()->shouldNotEqual($this);
    }

    function it_can_reduce_the_set_using_a_function() {
        $this->reduce(
            function ($carry, $item) {
                return $carry + $item;
            }, 2)
            ->shouldBe(12);
    }

    function it_can_filter_using_a_function() {
        $this->filter(function ($item) {
            return $item % 2;
        })
            ->shouldEqualValue(Collection::make($this->fixture->filteredSet));
    }
}
