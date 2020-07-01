<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcery\EventSourcing\DomainEvents;

/**
 * ImmediateEventDispatcher is a default (yet optional) implementation
 * of EventDispatcher. It will synchronously deliver domain events to
 * each event listener before returning to program execution.
 */
class ImmediateEventDispatcher implements EventDispatcher
{

    /** @var Listeners */
    private $listeners;

    public function __construct()
    {
        $this->listeners = Listeners::make();
    }

    /**
     * add an event listener to the dispatcher
     *
     * @param Listener $listener
     */
    public function addListener(Listener $listener): void
    {
        $this->listeners = $this->listeners->add($listener);
    }

    /**
     * dispatch domain events to listeners
     *
     * @param DomainEvents $events
     */
    public function dispatch(DomainEvents $events): void
    {
        $events->each(function (DomainEvent $event) {
            $this->listeners->each(function (Listener $listener) use ($event) {
                $listener->handle($event);
            });
        });
    }

    public function listeners(): Listeners
    {
        return $this->listeners;
    }
}
