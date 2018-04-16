<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\DomainEvent;

class TestCountingEvent implements DomainEvent {

    /** @var int $number */
    private $number;

    public function __construct(int $number = 0) {
        $this->number = $number;
    }

    public function number() : int {
        return $this->number;
    }
}