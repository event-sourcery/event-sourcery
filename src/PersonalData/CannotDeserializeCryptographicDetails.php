<?php namespace EventSourcery\EventSourcery\PersonalData;

/**
 * The CannotDeserializeCryptographicDetails exception is thrown
 * when an instance of CryptographicDetails is attempted to be
 * deserialized, but the encryption type could not be identified.
 */
class CannotDeserializeCryptographicDetails extends \Exception {}