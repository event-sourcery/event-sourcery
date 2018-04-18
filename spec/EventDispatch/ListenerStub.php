<?php namespace spec\EventSourcery\EventDispatch;

use EventSourcery\EventDispatch\Listener;
use EventSourcery\EventSourcing\DomainEvent;

class ListenerStub implements Listener {

    public function handle(DomainEvent $event) : void {

    }
}