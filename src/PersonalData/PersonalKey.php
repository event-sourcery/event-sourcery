<?php namespace EventSourcery\PersonalData;

use EventSourcery\EventSourcing\Id;
use EventSourcery\Serialization\SerializableValue;

class PersonalKey implements SerializableValue {

    private $key;

    public static function fromString(string $string): PersonalKey {
        return new static($string);
    }

    public function toString(): string {
        return $this->key;
    }

    public static function fromId(Id $id): PersonalKey {
        return static::fromString($id->toString());
    }

    public static function deserialize(string $string): PersonalKey {
        return new static($string);
    }

    public function serialize(): string {
        return $this->key;
    }

    private function __construct(string $key) {
        $this->key = $key;
    }
}