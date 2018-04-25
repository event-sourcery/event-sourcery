<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

interface SerializablePersonalDataValue extends SerializableValue {

    public function personalKey(): PersonalKey;
    public function serialize(): string;
    public static function deserialize(string $json);
}