<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class BoolEventStub implements DomainEvent {

    /** @var bool */
    public $bool;

    public function __construct(bool $bool) {
        $this->bool = $bool;
    }
}