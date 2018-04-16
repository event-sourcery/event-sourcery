<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcing\Listener;

class TestListener implements Listener {

    public function handle(DomainEvent $event) : void {

    }
}