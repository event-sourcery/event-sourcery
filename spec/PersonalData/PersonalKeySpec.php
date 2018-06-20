<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\PersonalKey;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class PersonalKeySpec extends ObjectBehavior {

    function let() {
        $this->beConstructedThrough('fromString', ['a person']);
    }

    function it_can_be_serialized() {
        $serialized = $this->serialize();
        $serialized->shouldBe('a person');

        $deserialized = PersonalKey::deserialize($serialized->getWrappedObject());
        expect($deserialized->toString())->shouldBe('a person');
    }

    function it_can_be_built_from_strings() {
        $id1 = (PersonalKey::fromString("hats"))->toString();
        $id2 = (PersonalKey::fromString("hats"))->toString();
        $id3 = (PersonalKey::fromString("cats"))->toString();

        expect($id1)->shouldEqual($id2);
        expect($id1)->shouldNotEqual($id3);
    }

    function it_can_be_compared_for_value() {
        $id1 = PersonalKey::fromString("hats");
        $id2 = PersonalKey::fromString("hats");
        $id3 = PersonalKey::fromString("cats");

        expect($id1)->equals($id2)->shouldBe(true);
        expect($id1)->equals($id3)->shouldBe(false);
    }
}
