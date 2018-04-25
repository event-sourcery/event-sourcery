<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\PersonalKey;
use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class PersonalKeySpec extends ObjectBehavior {

    function it_can_be_serialized() {
        $this->beConstructedThrough('fromString', ['key123']);
        $this->serialize()->shouldBe('key123');
    }

    function it_can_be_built_from_strings() {
        $id1 = (PersonalKey::fromString("hats"))->serialize();
        $id2 = (PersonalKey::fromString("hats"))->serialize();
        $id3 = (PersonalKey::fromString("cats"))->serialize();
        expect($id1)->shouldEqual($id2);
        expect($id1)->shouldNotEqual($id3);
    }
}
