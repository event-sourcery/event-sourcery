<?php namespace EventSourcery\EventSourcery\PersonalData;

/**
 * CryptographicDetailsDoNotContainKey is an exception that is thrown
 * when an encryption method attempts to access a key that isn't
 * present within the CryptographicDetails value object.
 */
class CryptographicDetailsDoNotContainKey extends \Exception {}