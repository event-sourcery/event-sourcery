<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\PersonalKey;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class PersonalKeySpec extends ObjectBehavior {

    function it_can_be_serialized() {
        $this->beConstructedThrough('fromString', ['key123']);

        $serialized = $this->serialize();
        $serialized->shouldBe('key123');

        $deserialized = PersonalKey::deserialize($serialized->getWrappedObject());
        expect($deserialized->toString())->shouldBe('key123');
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
