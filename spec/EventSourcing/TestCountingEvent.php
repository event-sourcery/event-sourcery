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

    public function serialize() : array {
        return [];
    }

    public static function deserialize(array $data) : DomainEvent {
        return TestDomainEvent::deserialize();
    }
}