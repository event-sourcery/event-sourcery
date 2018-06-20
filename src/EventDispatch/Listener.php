<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

/**
 * A listener is an object that is notified when domain events
 * are raised and persisted. Listener implementations include
 * any type of object that responds to events in any way,
 * including process managers and projections.
 *
 * Interface Listener
 * @package EventSourcery\EventSourcery\EventDispatch
 */
interface Listener {

    /**
     * handle() is called when an event is received from the dispatcher
     *
     * @param DomainEvent $event
     */
    public function handle(DomainEvent $event) : void;
}
