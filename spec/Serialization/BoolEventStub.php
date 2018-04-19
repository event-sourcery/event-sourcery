<?php namespace spec\EventSourcery\Serialization;

use EventSourcery\EventSourcing\DomainEvent;

class BoolEventStub implements DomainEvent {

    /** @var bool */
    public $bool;

    public function __construct(bool $bool) {
        $this->bool = $bool;
    }
}