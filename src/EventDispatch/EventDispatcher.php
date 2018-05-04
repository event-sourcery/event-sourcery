<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\EventSourcing\DomainEvents;

interface EventDispatcher {

    public function addListener(Listener $listener): void;
    public function dispatch(DomainEvents $events): void;
}
