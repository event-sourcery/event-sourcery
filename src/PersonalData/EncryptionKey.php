<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

interface EncryptionKey extends SerializableValue {
    public static function generate(): EncryptionKey;
}