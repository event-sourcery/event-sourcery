<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class EncryptedPersonalData implements SerializableValue {

    private $data;

    public function toString(): string {
        return $this->data;
    }

    public static function fromString($string) {
        return new static($string);
    }

    public function __construct($data) {
        $this->data = $data;
    }
}