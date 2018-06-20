<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\PersonalDataKey;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class PersonalDataKeySpec extends ObjectBehavior {

    function let() {
        $this->beConstructedThrough('fromString', ['personal data key']);
    }

    function it_can_generate_unique_values() {
        $id1 = (PersonalDataKey::generate())->serialize();
        $id2 = (PersonalDataKey::generate())->serialize();
        $id3 = (PersonalDataKey::generate())->serialize();

        expect($id1)->shouldNotEqual($id2);
        expect($id2)->shouldNotEqual($id3);
        expect($id1)->shouldNotEqual($id3);
    }

    function it_can_be_cast_to_and_from_strings() {
        $this->toString()->shouldBe('personal data key');
    }

    function it_can_be_serialized_and_deserialized() {
        $serialized = $this->serialize();
        $deserialized = PersonalDataKey::deserialize($serialized->getWrappedObject());

        expect($deserialized->toString())->toBe('personal data key');
    }
}
