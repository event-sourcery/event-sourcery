<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\Aggregate;
use EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcing\Id;
use EventSourcery\EventSourcing\StreamId;

class TestAggregate extends Aggregate {

    // this value in incremented by events
    private $number = 0;

    public static function create() {
        return new static;
    }

    public function appliedEventCount() : int {
        return $this->number;
    }

    public function raiseEvent(DomainEvent $event) {
        $this->raise($event);
    }

    // returns an arbitrary Id
    public function id() : Id {
        return StreamId::fromString("id");
    }

    protected function applyTestCountingEvent(TestCountingEvent $e) {
        $this->number += $e->number();
    }
}
