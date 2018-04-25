<?php namespace EventSourcery\PersonalData;

use EventSourcery\EventSourcing\Id;
use EventSourcery\Serialization\SerializableValue;

class PersonalKey implements SerializableValue {

    private $key;

    public static function fromString(string $string) {
        return new static($string);
    }

    public static function fromId(Id $id) {
        return static::fromString($id->toString());
    }

    public static function deserialize(string $string) {
        return new static($string);
    }

    public function serialize(): string {
        return $this->key;
    }

    private function __construct($key) {
        $this->key = $key;
    }
}