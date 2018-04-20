<?php namespace EventSourcery\PersonalData;

interface PersonalDataStore {
    function getData(PersonalDataKey $key): EncryptedPersonalData;
    function storeData(PersonalKey $personalKey, PersonalDataKey $dataKey, EncryptedPersonalData $data);
}