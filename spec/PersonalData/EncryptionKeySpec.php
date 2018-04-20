<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\CryptographicDetails;
use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class Pbkdf2EncryptionKeySpec extends ObjectBehavior {

    function it_represents_encryption_keys_and_initialization_vectors() {
        $this->beConstructedWith('key123', 'iv');
        $this->toString()->shouldBe('key123|||' . base64_encode('iv'));
    }

    function it_can_be_serialized() {
        $this->beConstructedWith('key123', 'iv');
        $serialized = $this->toString()->getWrappedObject();
        $deserialized = CryptographicDetails::fromString($serialized);
        expect($deserialized)->key()->toBe('key123');
        expect($deserialized)->initializationVector()->toBe('iv');
    }
}
