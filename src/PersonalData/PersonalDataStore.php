<?php namespace EventSourcery\PersonalData;

interface PersonalDataStore {
    function retrieveData(PersonalKey $personalKey, PersonalDataKey $key): PersonalData;
    function storeData(PersonalKey $personalKey, PersonalDataKey $dataKey, PersonalData $data);
}