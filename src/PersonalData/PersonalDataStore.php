<?php namespace EventSourcery\PersonalData;

interface PersonalDataStore {
    function getData(PersonalKey $personalKey, PersonalDataKey $key): PersonalData;
    function storeData(PersonalKey $personalKey, PersonalDataKey $dataKey, PersonalData $data);
}