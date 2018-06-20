<?php namespace EventSourcery\EventSourcery\PersonalData;

/**
 * CryptographicDetailsNotCompatibleWithEncryption is an exception
 * that is thrown when an encryption method attempts to use
 * cryptographic details that were generated for a different method.
 */
class CryptographicDetailsNotCompatibleWithEncryption extends \Exception {}