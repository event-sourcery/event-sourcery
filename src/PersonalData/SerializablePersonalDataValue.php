<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

/**
 * The SerializablePersonalDataValue interface is implemented
 * by value objects that contain protected personal data.
 *
 * Interface SerializablePersonalDataValue
 * @package EventSourcery\EventSourcery\PersonalData
 */
interface SerializablePersonalDataValue extends SerializableValue {

    /**
     * the factory method to build this data from erased state
     *
     * @param PersonalKey $personalKey
     * @return mixed
     */
    public static function fromErasedState(PersonalKey $personalKey);

    /**
     * the personal key is the identity of the person whom the
     * personal data identifies
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
     * deserialization function for reconstructing data from
     * a string
     * 
     * @param string $string
     */
    public static function deserialize(string $string);
}