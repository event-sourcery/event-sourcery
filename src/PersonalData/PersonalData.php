<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

/**
 * PersonalData is the value object that contains protected
 * personal data. This can be encrypted using an encryption method
 * which will return an instance of EncryptedPersonalData.
 */
class PersonalData implements SerializableValue {

    private $data;

    private function __construct(string $data) {
        $this->data = $data;
    }

    /**
     * create an instance of personal data from string
     *
     * @param string $string
     * @return PersonalData
     */
    public static function fromString(string $string): PersonalData {
        return new static($string);
    }

    /**
     * cast this instance of personal data to a string
     *
     * @return string
     */
    public function toString(): string {
        return $this->data;
    }

    /**
     * return a serialized for of this instance of personal data for
     * string-based persistence
     *
     * @return string
     */
    public function serialize(): string {
        return $this->data;
    }

    /**
     * instantiate an instance of personal data from string-based
     * persistence
     *
     * @param string $string
     * @return PersonalData
     */
    public static function deserialize(string $string): PersonalData {
        return new static($string);
    }
}