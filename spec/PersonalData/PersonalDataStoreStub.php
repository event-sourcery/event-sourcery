<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\CanNotFindPersonalDataByKey;
use EventSourcery\EventSourcery\PersonalData\PersonalData;
use EventSourcery\EventSourcery\PersonalData\PersonalDataKey;
use EventSourcery\EventSourcery\PersonalData\PersonalDataStore;
use EventSourcery\EventSourcery\PersonalData\PersonalKey;

class PersonalDataStoreStub implements PersonalDataStore {

    public $dataKeyData = [];

    function storeData(PersonalKey $personalKey, PersonalDataKey $dataKey, PersonalData $data) {
        $this->dataKeyData[$dataKey->toString()] = new StoredPersonalDataStub($personalKey, $data);
    }

    function retrieveData(PersonalKey $personalKey, PersonalDataKey $dataKey): PersonalData {
        if ( ! isset($this->dataKeyData[$dataKey->toString()])) {
            throw new CanNotFindPersonalDataByKey($dataKey->toString());
        }
        return $this->dataKeyData[$dataKey->toString()]->personalData;
    }

    function removeDataFor(PersonalKey $personalKey) {
        $this->dataKeyData = array_filter($this->dataKeyData, function(StoredPersonalDataStub $stored) use ($personalKey) {
            return $stored->personalKey->equals($personalKey);
        });
    }
}

class StoredPersonalDataStub {

    /** @var PersonalKey */
    public $personalKey;
    /** @var PersonalData */
    public $personalData;

    public function __construct(PersonalKey $personalKey, PersonalData $dataKey) {
        $this->personalKey = $personalKey;
        $this->personalData = $dataKey;
    }
}