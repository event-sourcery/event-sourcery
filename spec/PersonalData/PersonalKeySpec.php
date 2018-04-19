<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\PersonalKey;
use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class PersonalKeySpec extends ObjectBehavior {

    function it_can_be_serialized() {
        $this->beConstructedThrough('fromString', ['key123']);
        $this->toString()->shouldBe('key123');
    }

    function it_can_be_generated() {
        $id1 = (PersonalKey::generate())->toString();
        $id2 = (PersonalKey::generate())->toString();
        expect($id1)->shouldNotEqual($id2);
    }
}
