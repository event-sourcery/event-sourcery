<?php namespace EventSourcery\PersonalData;

interface PersonalDataEncrypter {
    function encrypt(EncryptionKey $encryptionKey, PersonalData $data): EncryptedPersonalData;
    function decrypt(EncryptionKey $encryptionKey, EncryptedPersonalData $data): PersonalData;
}