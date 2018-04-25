<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class EncryptedPersonalData implements SerializableValue {

    private $data;

    public function serialize(): string {
        return $this->data;
    }

    public static function deserialize($json) {
        return new static($json);
    }

    public function __construct($data) {
        $this->data = $data;
    }
}