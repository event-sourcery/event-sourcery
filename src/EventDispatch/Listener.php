<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

interface Listener {

    /**
     * handle() is called when an event is received from the dispatcher
     *
     * @param DomainEvent $event
     */
    public function handle(DomainEvent $event) : void;
}
