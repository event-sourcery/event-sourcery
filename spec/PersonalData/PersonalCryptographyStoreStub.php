<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\CouldNotFindCryptographyForPerson;
use EventSourcery\PersonalData\CryptographicDetails;
use EventSourcery\PersonalData\PersonalCryptographyStore;
use EventSourcery\PersonalData\PersonalKey;

class PersonalCryptographyStoreStub implements PersonalCryptographyStore {

    private $personCrypto = [];

    function addPerson(PersonalKey $person, CryptographicDetails $crypto) {
        $this->personCrypto[$person->toString()] = $crypto;
    }

    function getCryptographyFor(PersonalKey $person): CryptographicDetails {
        if ( ! isset($this->personCrypto[$person->toString()])) {
            throw new CouldNotFindCryptographyForPerson("No cryptography exists for person " . $person->toString());
        }
        return $this->personCrypto[$person->toString()];
    }

    function removePerson(PersonalKey $person) {
        unset($this->personCrypto[$person->toString()]);
    }
}