<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;
use Ramsey\Uuid\Uuid;

class PersonalDataKey implements SerializableValue {

    private $key;

    public static function generate(): PersonalDataKey {
        return new static(Uuid::uuid4()->toString());
    }

    public static function fromString($string) {
        return new static($string);
    }

    public function toString(): string {
        return $this->key;
    }

    private function __construct($key) {
        $this->key = $key;
    }
}