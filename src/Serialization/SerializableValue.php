<?php namespace EventSourcery\EventSourcery\Serialization;

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