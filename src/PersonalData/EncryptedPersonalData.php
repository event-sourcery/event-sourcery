<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

/**
 * EncryptedPersonalData is a value object for personal data that
 * has been encrypted. EncryptedPersonalData can be decrypted by
 * an encryption method. The decrypted data will be of type PersonalData.
 */
class EncryptedPersonalData implements SerializableValue {

    private $data;

    private function __construct($data) {
        $this->data = $data;
    }

    /**
     * construct an instance of encrypted personal data from string
     *
     * @param string $string
     * @return EncryptedPersonalData
     */
    public static function fromString(string $string): EncryptedPersonalData {
        return new static($string);
    }

    /**
     * cast the instance of encrypted personal data to a string
     *
     * @return string
     */
    public function toString(): string {
        return $this->data;
    }

    /**
     * serialize() returns an associated array form of the
     * value for persistence which will be deserialized when needed.
     *
     * the array should contain primitives for both keys and values.
     *
     * @return array
     */
    public function serialize(): array {
        return [
            'data' => $this->data
        ];
    }

    /**
     * deserialize() returns a value object from an associative array received
     * from persistence
     *
     * @param array $data
     * @return mixed
     */
    public static function deserialize(array $data): EncryptedPersonalData {
        return new static($data['data']);
    }
}