<?php namespace EventSourcery\EventDispatch;

use EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcing\DomainEvents;

class ImmediateEventDispatcher implements EventDispatcher {

    /** @var Listeners */
    private $listeners;

    public function __construct() {
        $this->listeners = Listeners::make();
    }

    public function addListener(Listener $listener) : void {
        $this->listeners = $this->listeners->add($listener);
    }

    public function dispatch(DomainEvents $events) : void {
        $events->each(function (DomainEvent $event) {
            $this->listeners->each(function (Listener $listener) use ($event) {
                $listener->handle($event);
            });
        });
    }
}
