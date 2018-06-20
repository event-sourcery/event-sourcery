<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;
use Ramsey\Uuid\Uuid;

/**
 * A PersonalDataKey is a key that refers to a specific instance
 * of protected personal data in the personal data store.
 */
class PersonalDataKey implements SerializableValue {

    private $key;

    private function __construct(string $key) {
        $this->key = $key;
    }

    /**
     * generate a new personal data key
     *
     * @return PersonalDataKey
     */
    public static function generate(): PersonalDataKey {
        return new static(Uuid::uuid4()->toString());
    }

    /**
     * return an instance of a personal data key based on
     * the supplied string
     *
     * @param string $string
     * @return PersonalDataKey
     */
    public static function fromString(string $string): PersonalDataKey {
        return new static($string);
    }

    /**
     * return a string representation of the personal data key
     * instance
     *
     * @return string
     */
    public function toString(): string {
        return $this->key;
    }

    /**
     * serialize() returns a string form of the value for persistence
     * which will be deserialized when needed
     *
     * @return string
     */
    public function serialize(): string {
        return $this->key;
    }

    /**
     * deserialize() returns a value object from a string received
     * from persistence
     *
     * @param string $string
     * @return mixed
     */
    public static function deserialize(string $string): PersonalDataKey {
        return new static($string);
    }
}