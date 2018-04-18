<?php namespace EventSourcery\Queries;

use EventSourcery\EventDispatch\Listener;

interface Projection extends Listener {

    public function name(): string;

    public function reset(): void;
}