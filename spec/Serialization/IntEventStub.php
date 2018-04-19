<?php namespace spec\EventSourcery\Serialization;

use EventSourcery\EventSourcing\DomainEvent;

class IntEventStub implements DomainEvent {

    /** @var int */
    public $int;

    public function __construct(int $int) {
        $this->int = $int;
    }
}