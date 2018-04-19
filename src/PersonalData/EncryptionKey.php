<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class EncryptionKey implements SerializableValue {

    private $key;

    public function toString(): string {
        return $this->key;
    }

    public static function fromString($string) {
        return new static($string);
    }

    private function __construct($key) {
        $this->key = $key;
    }
}