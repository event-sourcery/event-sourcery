<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\EventSourcing\Id;
use EventSourcery\EventSourcery\Serialization\SerializableValue;

class PersonalKey implements SerializableValue {

    private $key;

    private function __construct(string $key) {
        $this->key = $key;
    }

    public function toString(): string {
        return $this->key;
    }

    // factory
    public static function fromString(string $string): PersonalKey {
        return new static($string);
    }

    public static function fromId(Id $id): PersonalKey {
        return static::fromString($id->toString());
    }

    public function equals(PersonalKey $that): bool {
        return $this->key === $that->key;
    }

    // serialization
    public static function deserialize(string $string): PersonalKey {
        return new static($string);
    }

    public function serialize(): string {
        return $this->key;
    }
}