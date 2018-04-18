<?php namespace spec\EventSourcery\Queries;

use EventSourcery\EventDispatch\Listener;
use EventSourcery\EventSourcing\DomainEvent;

class ListenerStub implements Listener {

    public function handle(DomainEvent $event) : void {

    }
}