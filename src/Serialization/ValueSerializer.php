<?php namespace EventSourcery\Serialization;

use EventSourcery\PersonalData\PersonalData;
use EventSourcery\PersonalData\PersonalDataKey;
use EventSourcery\PersonalData\PersonalDataStore;
use EventSourcery\PersonalData\PersonalKey;
use EventSourcery\PersonalData\SerializablePersonalDataValue;

class ValueSerializer {

    /** @var PersonalDataStore */
    private $dataStore;

    public function __construct(PersonalDataStore $dataStore) {
        $this->dataStore = $dataStore;
    }

    public function serialize($value) {
        if ($value instanceof SerializablePersonalDataValue) {
            return $this->serializePersonalDataValue($value);
        } elseif ($value instanceof SerializableValue) {
            return $this->serializeValue($value);
        }
        return $value;
    }

    private function serializeValue(SerializableValue $value): string {
        return $value->serialize();
    }

    private function serializePersonalDataValue(SerializablePersonalDataValue $value): string {
        $dataKey = PersonalDataKey::generate();

        $this->dataStore->storeData($value->personalKey(), $dataKey, PersonalData::fromString($value->serialize()));

        return json_encode([
            'personalKey' => $value->personalKey()->serialize(),
            'dataKey'     => $dataKey->serialize(),
        ]);
    }

    public function deserializeValue(string $json) {
        
    }

    public function deserializePersonalValue(string $type, string $json): SerializablePersonalDataValue {
        $values = json_decode($json);

        $personalKey = PersonalKey::deserialize($values->personalKey);
        $dataKey = PersonalDataKey::deserialize($values->dataKey);

        return $type::deserialize($this->dataStore->retrieveData($personalKey, $dataKey)->toString());
    }
}