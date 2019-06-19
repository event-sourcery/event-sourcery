<?php namespace EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\PersonalData\PersonalData;
use EventSourcery\EventSourcery\PersonalData\PersonalDataKey;
use EventSourcery\EventSourcery\PersonalData\PersonalDataStore;
use EventSourcery\EventSourcery\PersonalData\PersonalKey;
use EventSourcery\EventSourcery\PersonalData\SerializablePersonalDataValue;

/**
 * The ValueSerializer is used for the ReflectionBasedDomainEventSerializer,
 * a default (yet optional) implementation of DomainEventSerializer.
 *
 * ValueSerializer's job is to serialize and deserialize value objects.
 *
 * SerializablePersonalDataValues require interaction with the PersonalDataStore
 * and the Personal Cryptographic Store.
 */
class ValueSerializer {

    /** @var PersonalDataStore */
    private $dataStore;

    /** @todo add serializer strategy pattern */

    public function __construct(PersonalDataStore $dataStore) {
        $this->dataStore = $dataStore;
    }

    /**
     * serialize a value object into its string-based persistence form
     *
     * @param $value
     * @return array
     */
    public function serialize($value) {
        if ($value instanceof SerializablePersonalDataValue) {
            return $this->serializePersonalDataValue($value);
        } elseif ($value instanceof SerializableValue) {
            return $this->serializeValue($value);
        }
        return $value;
    }

    /**
     * serialize a value that implements SerializableValue
     *
     * @param SerializableValue $value
     * @return array
     */
    private function serializeValue(SerializableValue $value): array {
        return $value->serialize();
    }

    /**
     * serialize a value that contains personal data
     *
     * @param SerializablePersonalDataValue $value
     * @return array
     */
    private function serializePersonalDataValue(SerializablePersonalDataValue $value): array {
        $dataKey    = PersonalDataKey::generate();
        $dataString = json_encode($value->serialize());

        $this->dataStore->storeData($value->personalKey(), $dataKey, PersonalData::fromString($dataString));

        return [
            'personalKey' => $value->personalKey()->serialize(),
            'dataKey'     => $dataKey->serialize(),
        ];
    }

    /**
     * deserialize a value that contains personal data
     *
     * @param string $type
     * @param string $json
     * @return SerializablePersonalDataValue
     */
    public function deserializePersonalValue(string $type, string $json): SerializablePersonalDataValue {
        $values = json_decode($json);

        $personalKey = PersonalKey::deserialize($values->personalKey);
        $dataKey     = PersonalDataKey::deserialize($values->dataKey);
        
        return $type::deserialize($this->dataStore->retrieveData($personalKey, $dataKey)->toString());
    }
}