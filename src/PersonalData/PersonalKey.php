<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\EventSourcing\Id;
use EventSourcery\EventSourcery\Serialization\SerializableValue;

/**
 * A PersonalKey is an identifier that refers to a specific
 * person for whom we are storing protected data.
 */
class PersonalKey implements SerializableValue {

    private $key;

    private function __construct(string $key) {
        $this->key = $key;
    }

    /**
     * return the string representation of this PersonalKey
     *
     * @return string
     */
    public function toString(): string {
        return $this->key;
    }

    /**
     * construct an instance of PersonalKey from a string
     * representation
     *
     * @param string $string
     * @return PersonalKey
     */
    public static function fromString(string $string): PersonalKey {
        return new static($string);
    }

    /**
     * construct an instance of PersonalKey from an Id
     *
     * @param Id $id
     * @return PersonalKey
     */
    public static function fromId(Id $id): PersonalKey {
        return static::fromString($id->toString());
    }

    /**
     * compare two instances of PersonalKey for equality
     *
     * @param PersonalKey $that
     * @return bool
     */
    public function equals(PersonalKey $that): bool {
        return $this->key === $that->key;
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
    public static function deserialize(string $string): PersonalKey {
        return new static($string);
    }
}