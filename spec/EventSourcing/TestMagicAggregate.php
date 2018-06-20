<?php namespace spec\EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcery\EventSourcing\MagicAggregate;
use EventSourcery\EventSourcery\EventSourcing\StreamId;

class TestMagicAggregate extends MagicAggregate {

    // this value in incremented by events
    private $number = 0;

    public static function create() {
        return new static;
    }

    public function appliedEventCount(): int {
        return $this->number;
    }

    public function raiseEvent(DomainEvent $event) {
        $this->raise($event);
    }

    // returns an arbitrary Id
    public function aggregateId(): StreamId {
        return StreamId::fromString("id");
    }

    protected function applyTestCountingEvent(TestCountingEvent $e) {
        $this->number += $e->number();
    }
}
