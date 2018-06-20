<?php namespace EventSourcery\EventSourcery\StreamProcessing;

use EventSourcery\EventSourcery\EventDispatch\Listener;

/**
 * A Projection is a type of event listener which will interpret
 * the events that it receives and update a read model for
 * system queries.
 *
 * Interface Projection
 * @package EventSourcery\EventSourcery\StreamProcessing
 */
interface Projection extends Listener {

    /**
     * return the name of the projection
     *
     * @return string
     */
    public function name(): string;

    /**
     * clear the entire projection's state
     */
    public function reset(): void;
}