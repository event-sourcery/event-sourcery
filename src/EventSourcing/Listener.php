<?php namespace EventSourcery\EventSourcing;

interface Listener {
    public function handle(DomainEvent $event) : void;
}
