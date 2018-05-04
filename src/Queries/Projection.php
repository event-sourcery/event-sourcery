<?php namespace EventSourcery\EventSourcery\Queries;

use EventSourcery\EventSourcery\EventDispatch\Listener;

interface Projection extends Listener {

    public function name(): string;

    public function reset(): void;
}