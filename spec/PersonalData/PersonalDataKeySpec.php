<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\PersonalDataKey;
use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class PersonalDataKeySpec extends ObjectBehavior {

    function it_can_be_serialized() {
        $this->beConstructedThrough('fromString', ['key123']);
        $this->serialize()->shouldBe('key123');
    }

    function it_can_be_deserialized() {
        $this->beConstructedThrough('deserialize', ['key234']);
        $this->toString()->shouldBe('key234');
    }

    function it_can_generate_random_values() {
        $id1 = (PersonalDataKey::generate())->serialize();
        $id2 = (PersonalDataKey::generate())->serialize();
        expect($id1)->shouldNotEqual($id2);
    }
}
