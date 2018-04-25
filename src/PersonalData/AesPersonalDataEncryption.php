<?php namespace EventSourcery\PersonalData;

class AesPersonalDataEncryption implements PersonalDataEncryption {

    function encrypt(CryptographicDetails $crypto, PersonalData $data): EncryptedPersonalData {
        return EncryptedPersonalData::deserialize(
            base64_encode(
                openssl_encrypt(
                    $data->serialize(),
                    'aes-256-cbc',
                    $crypto->key()->serialize(),
                    OPENSSL_RAW_DATA,
                    $crypto->iv()->toBinary()
                )
            )
        );
    }

    function decrypt(CryptographicDetails $crypto, EncryptedPersonalData $data): PersonalData {
        return PersonalData::deserialize(
            openssl_decrypt(
                base64_decode($data->serialize()),
                'aes-256-cbc',
                $crypto->key()->serialize(),
                OPENSSL_RAW_DATA,
                $crypto->iv()->toBinary()
            )
        );
    }
}
