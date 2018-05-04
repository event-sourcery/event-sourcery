<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

interface SerializablePersonalDataValue extends SerializableValue {

    public function personalKey(): PersonalKey;
    public function serialize(): string;
    public static function deserialize(string $string);
}