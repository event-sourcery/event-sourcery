<?php namespace EventSourcery\EventSourcery\Serialization;

interface SerializableValue {
    public function serialize(): string;
    public static function deserialize(string $string);
}