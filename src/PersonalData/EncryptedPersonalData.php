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
     * serialize the instance of encrypted personal data to string for persistence
     *
     * @return string
     */
    public function serialize(): string {
        return $this->data;
    }

    /**
     * deserialize the instance of encrypted personal data from string-based persistence
     *
     * @param string $string
     * @return EncryptedPersonalData
     */
    public static function deserialize(string $string): EncryptedPersonalData {
        return new static($string);
    }
}