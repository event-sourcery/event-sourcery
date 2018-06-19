<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\EventSourcing\DomainEvents;

interface EventDispatcher {

    /**
     * add an event listener to the dispatcher
     *
     * @param Listener $listener
     */
    public function addListener(Listener $listener): void;

    /**
     * dispatch domain events to listeners
     *
     * @param DomainEvents $events
     */
    public function dispatch(DomainEvents $events): void;
}
