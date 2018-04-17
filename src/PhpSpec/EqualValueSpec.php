<?php namespace EventSourcery\PhpSpec;

use PhpSpec\ObjectBehavior;

class EqualValueSpec extends ObjectBehavior {

    public function it_compares_two_value_objects_for_equality() {
        expect(ValueStub::fromString('value 1'))->shouldEqualValue(ValueStub::fromString('value 1'));
        expect(ValueStub::fromString('value 1'))->shouldNotEqualValue(ValueStub::fromString('value 2'));
    }
}