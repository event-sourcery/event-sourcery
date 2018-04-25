<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\EncryptionKey;
use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class EncryptionKeySpec extends ObjectBehavior {

    function it_generates_keys() {
        $key1 = EncryptionKey::generate()->serialize();
        $key2 = EncryptionKey::generate()->serialize();

        expect($key1)->toNotBe($key2);
    }

    function it_can_be_serialized() {
        $serialized = EncryptionKey::generate()->serialize();
        $deserialized = EncryptionKey::deserialize($serialized);

        expect($deserialized->serialize())->toEqual($serialized);
    }
}
