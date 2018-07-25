<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use PhpSpec\ObjectBehavior;

class PersonalDataSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedThrough('fromString', ['very personal data']);
    }

    function it_can_be_cast_to_and_from_strings() {
        $this->toString()->shouldBe('very personal data');
    }
}
