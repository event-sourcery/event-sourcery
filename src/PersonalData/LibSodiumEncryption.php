<?php namespace EventSourcery\EventSourcery\PersonalData;

class LibSodiumEncryption implements PersonalDataEncryption {

    function generateCryptographicDetails(): CryptographicDetails {

        return new CryptographicDetails('libsodium', [
            'secretKey'  => sodium_crypto_secretbox_keygen(),
            'nonce'      => random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES),
        ]);
    }

    function encrypt(PersonalData $data, CryptographicDetails $crypto): EncryptedPersonalData {

        if ( ! $crypto->type() == 'libsodium') {
            throw new CryptographicDetailsNotCompatibleWithEncryption("{$crypto->type()} received, expected 'libsodium'");
        }

        $data      = $data->toString();
        $secretKey = $crypto->key('secretKey');
        $nonce     = $crypto->key('nonce');

        $encrypted = sodium_crypto_secretbox($data, $nonce, $secretKey);

        return EncryptedPersonalData::deserialize($encrypted);
    }

    function decrypt(EncryptedPersonalData $data, CryptographicDetails $crypto): PersonalData {

        if ( ! $crypto->type() == 'libsodium') {
            throw new CryptographicDetailsNotCompatibleWithEncryption("{$crypto->type()} received, expected 'libsodium'");
        }

        $encrypted = $data->toString();
        $secretKey = $crypto->key('secretKey');
        $nonce     = $crypto->key('nonce');

        $decrypted = sodium_crypto_secretbox_open($encrypted, $nonce, $secretKey);

        return PersonalData::deserialize($decrypted);
    }
}