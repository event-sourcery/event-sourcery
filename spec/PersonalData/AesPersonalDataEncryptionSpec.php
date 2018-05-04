<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\AesPersonalDataEncryption;
use EventSourcery\EventSourcery\PersonalData\CryptographicDetails;
use EventSourcery\EventSourcery\PersonalData\InitializationVector;
use EventSourcery\EventSourcery\PersonalData\Pbkdf2AesKeyGenerator;
use EventSourcery\EventSourcery\PersonalData\EncryptionKey;
use EventSourcery\EventSourcery\PersonalData\PersonalData;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class AesPersonalDataEncryptionSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType(AesPersonalDataEncryption::class);
    }

    function it_can_encrypt_and_decrypt_personal_data() {
        $encryptionKey = EncryptionKey::generate();
        $initializationVector = InitializationVector::generate(16);

        $crypto = new CryptographicDetails($encryptionKey, $initializationVector);

        $encrypted = $this->encrypt($crypto, PersonalData::deserialize("i like hats"))->getWrappedObject();
        $decrypted = $this->decrypt($crypto, $encrypted)->serialize()->getWrappedObject();

        expect($decrypted)->toBe("i like hats");
    }
}
