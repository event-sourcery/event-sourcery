<?php namespace EventSourcery\EventSourcery\EventSourcing;

/**
 * InvalidAggregateVersionWhenBuildingFromEvents is an exception that is
 * thrown when an aggregate attempts to rebuild its state from a collection
 * of StreamEvents but arrives at an unexpected Stream Version.
 */
class UnexpectedAggregateVersionWhenBuildingFromEvents extends \Exception {}