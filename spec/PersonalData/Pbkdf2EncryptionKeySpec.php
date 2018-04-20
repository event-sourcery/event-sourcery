<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\Pbkdf2EncryptionKey;
use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class Pbkdf2EncryptionKeySpec extends ObjectBehavior {

    function it_generates_keys() {
        $key1 = Pbkdf2EncryptionKey::generate()->toString();
        $key2 = Pbkdf2EncryptionKey::generate()->toString();

        expect($key1)->toNotBe($key2);
    }

    function it_can_be_serialized() {
        $serialized = Pbkdf2EncryptionKey::generate()->toString();
        $deserialized = Pbkdf2EncryptionKey::fromString($serialized);

        expect($deserialized->toString())->toEqual($serialized);
    }
}
