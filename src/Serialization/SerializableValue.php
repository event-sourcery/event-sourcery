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
     * serialize() returns a string form of the value for persistence
     * which will be deserialized when needed
     *
     * @return string
     */
    public function serialize(): string;

    /**
     * deserialize() returns a value object from a string received
     * from persistence
     *
     * @param string $string
     * @return mixed
     */
    public static function deserialize(string $string);
}