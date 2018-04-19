<?php namespace EventSourcery\PersonalData;

interface PersonalDataStore {
    function getData(PersonalDataKey $key): PersonalData;
    function storeData(PersonalDataKey $personalKey, PersonalData $data);
}