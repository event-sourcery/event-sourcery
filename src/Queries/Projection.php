<?php namespace EventSourcery\EventSourcery\Queries;

use EventSourcery\EventSourcery\EventDispatch\Listener;

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