<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\PersonalData;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class PersonalDataSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedThrough('fromString', ['very personal data']);
    }

    function it_can_be_cast_to_and_from_strings() {
        $this->toString()->shouldBe('very personal data');
    }

    function it_can_be_serialized_and_deserialized() {
        $serialized = $this->serialize();

        $deserialized = PersonalData::deserialize($serialized->getWrappedObject());
        expect($deserialized->toString())->toBe('very personal data');
    }
}
