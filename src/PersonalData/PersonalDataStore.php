<?php namespace EventSourcery\PersonalData;

interface PersonalDataStore {
    function getData(PersonalDataKey $key): PersonalData;
    function storeData(PersonalKey $personalKey, PersonalDataKey $personalDataKey, PersonalData $data);
}