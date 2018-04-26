<?php namespace spec\EventSourcery\PersonalData;

use EventSourcery\PersonalData\PersonalData;
use EventSourcery\PersonalData\PersonalDataKey;
use EventSourcery\PersonalData\PersonalDataStore;
use EventSourcery\PersonalData\PersonalKey;

class PersonalDataStoreStub implements PersonalDataStore {

    private $dataKeyData = [];

    function storeData(PersonalKey $personalKey, PersonalDataKey $dataKey, PersonalData $data) {
        $this->dataKeyData[$dataKey->toString()] = $data->serialize();
    }

    function retrieveData(PersonalKey $personalKey, PersonalDataKey $dataKey): PersonalData {
        return PersonalData::deserialize($this->dataKeyData[$dataKey->toString()]);
    }
}