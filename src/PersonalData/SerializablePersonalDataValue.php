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
     * the wasErased method returns true if built fromErasedState.
     * due to the requirements for individual value objects, this must
     * be implemented manually
     *
     * @return bool
     */
    public function wasErased(): bool;

    /**
     * the personal key is the identity of the person whom the
     * personal data identifies
     *
     * @return PersonalKey
     */
    public function personalKey(): PersonalKey;

    /**
     * serialization function for storing data as an array
     * the array should contain only primitives for both
     * keys and values
     *
     * @return array
     */
    public function serialize(): array;

    /**
     * deserialization function for reconstructing data from
     * a string
     *
     * @param array $data
     */
    public static function deserialize(array $data);
}