<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class PersonalData implements SerializableValue {

    private $data;

    public function serialize(): string {
        return $this->data;
    }

    public static function deserialize(string $string): PersonalData {
        return new static($string);
    }

    public function __construct(string $data) {
        $this->data = $data;
    }

    public static function fromString(string $string): PersonalData {
        return new static($string);
    }

    public function toString(): string {
        return $this->data;
    }
}