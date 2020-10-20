<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class ScalarFieldStub implements DomainEvent {

    public $unknown;

    public function __construct($unknown) {
        $this->unknown = $unknown;
    }
}