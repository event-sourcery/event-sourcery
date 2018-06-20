<?php namespace EventSourcery\EventSourcery\PersonalData;

/**
 * LibSodiumEncryption is an encryption method provided with PHP 7.2
 * that seeks to implement quality encryption in an easy to use
 * abstraction.
 */
class LibSodiumEncryption implements PersonalDataEncryption {

    /**
     * generate new cryptographic details for a person specific to
     * this encryption method
     *
     * @return CryptographicDetails
     */
    function generateCryptographicDetails(): CryptographicDetails {

        return new CryptographicDetails('libsodium', [
            'secretKey'  => sodium_crypto_secretbox_keygen(),
            'nonce'      => random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES),
        ]);
    }

    /**
     * encrypt personal data and return an encrypted form
     *
     * @param PersonalData $data
     * @param CryptographicDetails $crypto
     * @throws CryptographicDetailsDoNotContainKey
     * @throws CryptographicDetailsNotCompatibleWithEncryption
     * @return EncryptedPersonalData
     */
    function encrypt(PersonalData $data, CryptographicDetails $crypto): EncryptedPersonalData {

        if ( ! $crypto->encryption() == 'libsodium') {
            throw new CryptographicDetailsNotCompatibleWithEncryption("{$crypto->encryption()} received, expected 'libsodium'");
        }

        $data      = $data->toString();
        $secretKey = $crypto->key('secretKey');
        $nonce     = $crypto->key('nonce');

        $encrypted = sodium_crypto_secretbox($data, $nonce, $secretKey);

        return EncryptedPersonalData::deserialize($encrypted);
    }

    /**
     * decrypted encrypted personal data and return a decrypted form
     *
     * @param EncryptedPersonalData $data
     * @param CryptographicDetails $crypto
     * @throws CryptographicDetailsDoNotContainKey
     * @throws CryptographicDetailsNotCompatibleWithEncryption
     * @return PersonalData
     */
    function decrypt(EncryptedPersonalData $data, CryptographicDetails $crypto): PersonalData {

        if ( ! $crypto->encryption() == 'libsodium') {
            throw new CryptographicDetailsNotCompatibleWithEncryption("{$crypto->encryption()} received, expected 'libsodium'");
        }

        $encrypted = $data->toString();
        $secretKey = $crypto->key('secretKey');
        $nonce     = $crypto->key('nonce');

        $decrypted = sodium_crypto_secretbox_open($encrypted, $nonce, $secretKey);

        return PersonalData::deserialize($decrypted);
    }
}