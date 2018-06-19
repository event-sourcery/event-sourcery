<?php namespace EventSourcery\EventSourcery\PersonalData;

interface PersonalDataEncryption {
    function encrypt(PersonalData $data, CryptographicDetails $crypto): EncryptedPersonalData;
    function decrypt(EncryptedPersonalData $data, CryptographicDetails $crypto): PersonalData;
}