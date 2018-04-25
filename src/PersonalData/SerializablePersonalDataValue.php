<?php namespace EventSourcery\PersonalData;

interface SerializablePersonalDataValue {
    public function personalKey(): PersonalKey;
    public function toString(): string;
    public static function fromString(PersonalKey $key, string $string);
}