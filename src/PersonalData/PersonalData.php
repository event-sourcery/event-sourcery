<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

/**
 * PersonalData is the value object that contains protected
 * personal data. This can be encrypted using an encryption method
 * which will return an instance of EncryptedPersonalData.
 */
class PersonalData {

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
}