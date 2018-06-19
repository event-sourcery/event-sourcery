<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;
use Ramsey\Uuid\Uuid;

class PersonalDataKey implements SerializableValue {

    private $key;

    private function __construct(string $key) {
        $this->key = $key;
    }

    public static function generate(): PersonalDataKey {
        return new static(Uuid::uuid4()->toString());
    }

    public static function fromString(string $string): PersonalDataKey {
        return new static($string);
    }

    public function toString(): string {
        return $this->key;
    }

    // serialization
    public static function deserialize(string $string): PersonalDataKey {
        return new static($string);
    }

    public function serialize(): string {
        return $this->key;
    }
}