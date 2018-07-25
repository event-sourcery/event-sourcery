<?php namespace EventSourcery\EventSourcery\Serialization;

/**
 * The SerializableValue is implemented in value objects that
 * appear within Commands or DomainEvents.
 *
 * When these Commands or DomainEvents are serialized into
 * storage, these methods will be called to do the work.
 *
 * Interface SerializableValue
 * @package EventSourcery\EventSourcery\Serialization
 */
interface SerializableValue {

    /**
     * serialize() returns an associated array form of the
     * value for persistence which will be deserialized when needed.
     *
     * the array should contain primitives for both keys and values.
     *
     * @return array
     */
    public function serialize(): array;

    /**
     * deserialize() returns a value object from an associative
     * array received from persistence
     *
     * @param array $data
     * @return mixed
     */
    public static function deserialize(array $data);
}