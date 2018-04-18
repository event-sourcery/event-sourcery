<?php namespace EventSourcery\EventSourcing\Serialization;

interface SerializableValue {
    public function toString(): string;
    public static function fromString($string);
}