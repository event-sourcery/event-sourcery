<?php namespace EventSourcery\EventSourcery\PersonalData;

/**
 * CanNotFindPersonalDataByKey is an exception that is thrown
 * when personal data is queried from the personal data store
 * but cannot be found based on the provided personal data key
 */
class CanNotFindPersonalDataByKey extends \Exception {}