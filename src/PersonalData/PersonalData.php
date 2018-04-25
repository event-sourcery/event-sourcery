<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class PersonalData implements SerializableValue {

    private $data;

    public function serialize(): string {
        return $this->data;
    }

    public static function deserialize(string $json) {
        return new static($json);
    }

    public function __construct($data) {
        $this->data = $data;
    }
}