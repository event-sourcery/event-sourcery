<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\PersonalDataKey;
use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class PersonalDataSpec extends ObjectBehavior {

    function it_can_be_serialized() {
        $this->beConstructedThrough('deserialize', ['key123']);
        $this->serialize()->shouldBe('key123');
    }

    function it_can_cast_to_and_from_string() {
        $this->beConstructedThrough('fromString', ['key234']);
        $this->toString()->shouldBe('key234');
    }
}
