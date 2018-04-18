<?php namespace EventSourcery\EventDispatch;

use EventSourcery\EventSourcing\DomainEvents;

interface EventDispatcher {

    public function addListener(Listener $listener): void;

    public function dispatch(DomainEvents $events): void;
}
