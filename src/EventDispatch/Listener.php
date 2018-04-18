<?php namespace EventSourcery\EventDispatch;

use EventSourcery\EventSourcing\DomainEvent;

interface Listener {
    public function handle(DomainEvent $event) : void;
}
