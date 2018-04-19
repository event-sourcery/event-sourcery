<?php namespace spec\EventSourcery\PersonalData;

use PhpSpec\ObjectBehavior;

class EncryptionKeySpec extends ObjectBehavior {

    function it_can_be_serialized() {
        $this->beConstructedThrough('fromString', ['key123']);
        $this->toString()->shouldBe('key123');
    }
}
