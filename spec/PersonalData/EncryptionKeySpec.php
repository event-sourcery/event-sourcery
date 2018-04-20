<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\EncryptionKey;
use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class EncryptionKeySpec extends ObjectBehavior {

    function it_generates_keys() {
        $key1 = EncryptionKey::generate()->toString();
        $key2 = EncryptionKey::generate()->toString();

        expect($key1)->toNotBe($key2);
    }

    function it_can_be_serialized() {
        $serialized = EncryptionKey::generate()->toString();
        $deserialized = EncryptionKey::fromString($serialized);

        expect($deserialized->toString())->toEqual($serialized);
    }
}
