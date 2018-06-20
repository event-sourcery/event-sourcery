<?php namespace EventSourcery\EventSourcery\PersonalData;

/**
 * PersonalDataEncryption is an encryption method that can be
 * used to encrypt PersonalData and return EncryptedPersonalData
 * or decrypt EncryptedPersonalData to return PersonalData.
 *
 * Interface PersonalDataEncryption
 * @package EventSourcery\EventSourcery\PersonalData
 */
interface PersonalDataEncryption {

    /**
     * generate new cryptographic details for a person specific to
     * this encryption method
     *
     * @return CryptographicDetails
     */
    function generateCryptographicDetails(): CryptographicDetails;

    /**
     * encrypt personal data and return an encrypted form
     *
     * @param PersonalData $data
     * @param CryptographicDetails $crypto
     * @throws CryptographicDetailsDoNotContainKey
     * @throws CryptographicDetailsNotCompatibleWithEncryption
     * @return EncryptedPersonalData
     */
    function encrypt(PersonalData $data, CryptographicDetails $crypto): EncryptedPersonalData;

    /**
     * decrypted encrypted personal data and return a decrypted form
     *
     * @param EncryptedPersonalData $data
     * @param CryptographicDetails $crypto
     * @throws CryptographicDetailsDoNotContainKey
     * @throws CryptographicDetailsNotCompatibleWithEncryption
     * @return PersonalData
     */
    function decrypt(EncryptedPersonalData $data, CryptographicDetails $crypto): PersonalData;
}