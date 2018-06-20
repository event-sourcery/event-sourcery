<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\EncryptedPersonalData;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class EncryptedPersonalDataSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedThrough('fromString', ['key123']);
    }

    function it_can_be_cast_to_and_from_strings() {
        $this->toString()->shouldBe('key123');
    }

    function it_can_be_serialized_and_deserialized() {
        $serialized = $this->serialize();

        $deserialized = EncryptedPersonalData::deserialize($serialized->getWrappedObject());
        expect($deserialized->toString())->toBe('key123');
    }
}
