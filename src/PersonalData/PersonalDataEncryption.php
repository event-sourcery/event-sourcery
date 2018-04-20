<?php namespace EventSourcery\PersonalData;

interface PersonalDataEncryption {
    function encrypt(CryptographicDetails $crypto, PersonalData $data): EncryptedPersonalData;
    function decrypt(CryptographicDetails $encryptionKey, EncryptedPersonalData $data): PersonalData;
}