<?php namespace EventSourcery\PersonalData;

interface PersonalEncryptionKeyStore {
    function addPerson(PersonalKey $person);
    function getEncryptionKeyFor(PersonalKey $person): EncryptionKey;
    function removePerson(PersonalKey $person);
}