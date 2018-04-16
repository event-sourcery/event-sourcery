<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\DomainEvent;

class TestDomainEvent implements DomainEvent {

    /** @var int */
    private $number;

    public function __construct(int $number = 0) {
        $this->number = $number;
    }

    public function number() {
        return $this->number;
    }
}
