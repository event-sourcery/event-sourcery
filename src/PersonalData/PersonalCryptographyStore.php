<?php namespace EventSourcery\EventSourcery\PersonalData;

interface PersonalCryptographyStore {
    function addPerson(PersonalKey $person, CryptographicDetails $crypto);
    function getCryptographyFor(PersonalKey $person): CryptographicDetails;
    function removePerson(PersonalKey $person);
}