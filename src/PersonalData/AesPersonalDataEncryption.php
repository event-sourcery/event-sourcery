<?php namespace EventSourcery\PersonalData;

class AesPersonalDataEncryption implements PersonalDataEncryption {

    function encrypt(CryptographicDetails $crypto, PersonalData $data): EncryptedPersonalData {
        return EncryptedPersonalData::fromString(
            base64_encode(openssl_encrypt($data->toString(), 'aes-256-cbc', $crypto->key()->toString(), OPENSSL_RAW_DATA, $crypto->iv()->toBinary()))
        );
    }

    function decrypt(CryptographicDetails $crypto, EncryptedPersonalData $data): PersonalData {
        return PersonalData::fromString(
            openssl_decrypt(base64_decode($data->toString()), 'aes-256-cbc', $crypto->key()->toString(), OPENSSL_RAW_DATA, $crypto->iv()->toBinary())
        );
    }
}
