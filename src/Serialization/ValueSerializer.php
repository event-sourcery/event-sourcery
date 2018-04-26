<?php namespace EventSourcery\Serialization;

use EventSourcery\PersonalData\CouldNotFindCryptographyForPerson;
use EventSourcery\PersonalData\CryptographicDetails;
use EventSourcery\PersonalData\EncryptionKey;
use EventSourcery\PersonalData\InitializationVector;
use EventSourcery\PersonalData\PersonalCryptographyStore;
use EventSourcery\PersonalData\PersonalData;
use EventSourcery\PersonalData\PersonalDataEncryption;
use EventSourcery\PersonalData\PersonalDataKey;
use EventSourcery\PersonalData\PersonalDataStore;
use EventSourcery\PersonalData\SerializablePersonalDataValue;

class ValueSerializer {

    /** @var PersonalCryptographyStore */
    private $crypto;
    /** @var PersonalDataStore */
    private $dataStore;
    /** @var PersonalDataEncryption */
    private $encryption;

    public function __construct(PersonalCryptographyStore $crypto, PersonalDataStore $dataStore, PersonalDataEncryption $encryption) {
        $this->crypto    = $crypto;
        $this->dataStore = $dataStore;
        $this->encryption = $encryption;
    }

    public function serialize($value) {
        if ($value instanceof SerializableValue) {
            return $this->serializeValue($value);
        } elseif ($value instanceof SerializablePersonalDataValue) {
            return $this->serializePersonalDataValue($value);
        }

        return $value;
    }

    private function serializeValue(SerializableValue $value): string {
        return $value->serialize();
    }

    private function serializePersonalDataValue(SerializablePersonalDataValue $value): string {
        try {
            $crypto = $this->crypto->getCryptographyFor($value->personalKey());
        } catch (CouldNotFindCryptographyForPerson $e) {
            $crypto = new CryptographicDetails(
                EncryptionKey::generate(),
                InitializationVector::generate()
            );
            $this->crypto->addPerson($value->personalKey(), $crypto);
        }

        $dataKey = PersonalDataKey::generate();

        $this->dataStore->storeData($value->personalKey(), $dataKey, PersonalData::fromString($value->serialize()));

        return json_encode([
            'personalKey' => $value->personalKey()->serialize(),
            'dataKey'     => $dataKey,
        ]);
    }

    public function deserializeValue(string $json) {
        
    }

    public function deserializePersonalValue(string $type, string $json) {
        $values = json_decode($json);

        $personalKey = $values->personalKey;
        $dataKey = $values->dataKey;

        $crypto = $this->crypto->getCryptographyFor($personalKey);
        $data = $this->dataStore->retrieveData($personalKey, $dataKey);

        $value = $this->encryption->decrypt($crypto, $data);

        return $type::deserialize($value);
    }
}