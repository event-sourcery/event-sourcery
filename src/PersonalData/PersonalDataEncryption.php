<?php namespace EventSourcery\EventSourcery\PersonalData;

interface PersonalDataEncryption {

    /**
     * encrypt personal data and return an encrypted form
     *
     * @param PersonalData $data
     * @param CryptographicDetails $crypto
     * @return EncryptedPersonalData
     */
    function encrypt(PersonalData $data, CryptographicDetails $crypto): EncryptedPersonalData;

    /**
     * decrypted encrypted personal data and return a decrypted form
     *
     * @param EncryptedPersonalData $data
     * @param CryptographicDetails $crypto
     * @return PersonalData
     */
    function decrypt(EncryptedPersonalData $data, CryptographicDetails $crypto): PersonalData;
}