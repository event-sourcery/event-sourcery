<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

interface SerializablePersonalDataValue extends SerializableValue {

    /**
     * the personal key is the identity of the person whom the personal data identifies
     *
     * @return PersonalKey
     */
    public function personalKey(): PersonalKey;

    /**
     * serialization function for storing data as a string
     *
     * @return string
     */
    public function serialize(): string;

    /**
     * deserialization function for reconstructing data from a string
     * 
     * @param string $string
     */
    public static function deserialize(string $string);
}