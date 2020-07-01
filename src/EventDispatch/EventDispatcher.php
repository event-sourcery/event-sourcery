<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\EventSourcing\DomainEvents;

/**
 * An EventDispatcher is first loaded with listeners. Then
 * once the dispatch() method receives a collection of
 * DomainEvents, each event is handed of to each listener.
 *
 * Interface EventDispatcher
 * @package EventSourcery\EventSourcery\EventDispatch
 */
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

    /**
     * get a list of all event listeners
     * 
     * @return Listeners
     */
    public function listeners(): Listeners;
}
