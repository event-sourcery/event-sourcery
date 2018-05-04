<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

interface Listener {
    public function handle(DomainEvent $event) : void;
}
