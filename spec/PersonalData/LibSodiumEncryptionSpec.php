<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\CryptographicDetailsDoNotContainKey;
use EventSourcery\EventSourcery\PersonalData\EncryptedPersonalData;
use EventSourcery\EventSourcery\PersonalData\LibSodiumEncryption;
use EventSourcery\EventSourcery\PersonalData\CryptographicDetails;
use EventSourcery\EventSourcery\PersonalData\InitializationVector;
use EventSourcery\EventSourcery\PersonalData\Pbkdf2AesKeyGenerator;
use EventSourcery\EventSourcery\PersonalData\EncryptionKey;
use EventSourcery\EventSourcery\PersonalData\PersonalData;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class LibSodiumEncryptionSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType(LibSodiumEncryption::class);
    }

    function it_can_generate_new_encryption_keys() {
        $crypto = $this->generateCryptographicDetails();
        $crypto->shouldHaveType(CryptographicDetails::class);
        $crypto->encryption()->shouldBe('libsodium');

        $crypto->shouldNotThrow(CryptographicDetailsDoNotContainKey::class)->during('key', ['secretKey']);
    }

    function it_can_encrypt_strings() {
        $crypto = $this->generateCryptographicDetails();
        $data = PersonalData::fromString('this is a string');

        $encrypted = $this->encrypt($data, $crypto);
        $encrypted->shouldHaveType(EncryptedPersonalData::class);

        $decrypted = $this->decrypt($encrypted, $crypto);
        $decrypted->shouldHaveType(PersonalData::class);

        $decrypted->toString()->shouldBe('this is a string');
    }
}
