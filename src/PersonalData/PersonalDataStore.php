<?php namespace EventSourcery\PersonalData;

interface ProtectedDataStore {
    function getData(ProtectedDataKey $key): ProtectedData;
    function storeData(PersonalDataKey $personalKey, PersonalData $data);
}