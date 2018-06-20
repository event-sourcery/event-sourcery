<?php namespace EventSourcery\EventSourcery\PersonalData;

/**
 * CanNotFindCryptographyForPerson is an exception that is thrown
 * when cryptographic details for a person have been requested
 * but cannot be found within the personal cryptography store.
 */
class CanNotFindCryptographyForPerson extends \Exception {}