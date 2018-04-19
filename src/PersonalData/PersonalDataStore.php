<?php namespace EventSourcery\PersonalData;

interface PersonalDataStore {
    function getData(ProtectedDataKey $key): ProtectedData;
    function storeData(PersonalDataKey $personalKey, PersonalData $data);
}