<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

class CryptographicDetails implements SerializableValue {

    private $details;

    public function serialize(): string {
        return json_encode($this->details);
    }

    public static function deserialize(string $string) {
        return new static($string);
    }

    public function __construct(array $details) {
        $this->details = $details;
    }

    public function key($name): string {
        if ( ! isset($this->details[$name])) {
            throw new CryptographicDetailsDoNotContainKey($name);
        }
        return $this->details[$name];
    }
}