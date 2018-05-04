<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class IntEventStub implements DomainEvent {

    /** @var int */
    public $int;

    public function __construct(int $int) {
        $this->int = $int;
    }
}