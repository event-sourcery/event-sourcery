<?php namespace EventSourcery\EventSourcery\PersonalData;

/**
 * A PersonalDataStore is the storage mechanism which holds
 * encrypted personal data. Each instance of personal data
 * is identified by the person to whom it belongs (PersonalKey)
 * and a unique key that represents the data instance
 * (PersonalDataKey)
 *
 * Interface PersonalDataStore
 * @package EventSourcery\EventSourcery\PersonalData
 */
interface PersonalDataStore {

    /**
     * retrieve personal data (identified by data key) for a person (identified by personal key)
     *
     * @param PersonalKey $personalKey
     * @param PersonalDataKey $dataKey
     * @return PersonalData
     */
    function retrieveData(PersonalKey $personalKey, PersonalDataKey $dataKey): PersonalData;

    /**
     * store personal data (identified by a data key) for a person (identified by a personal key)
     *
     * @param PersonalKey $personalKey
     * @param PersonalDataKey $dataKey
     * @param PersonalData $data
     */
    function storeData(PersonalKey $personalKey, PersonalDataKey $dataKey, PersonalData $data): void;

    /**
     * remove all personal data for a person (identified by a personal key)
     *
     * @param PersonalKey $personalKey
     */
    function removeDataFor(PersonalKey $personalKey): void;
}