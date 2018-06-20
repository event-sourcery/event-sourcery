<?php namespace spec\EventSourcery\EventSourcery\StreamProcessing;

use EventSourcery\EventSourcery\EventDispatch\Listener;
use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class ListenerStub implements Listener {

    public function handle(DomainEvent $event) : void {

    }
}