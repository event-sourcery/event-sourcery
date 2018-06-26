<?php namespace spec\EventSourcery\EventSourcery\Commands\Stubs;

use EventSourcery\EventSourcery\Commands\Command;
use EventSourcery\EventSourcery\EventSourcing\EventStore;
use spec\EventSourcery\EventSourcery\EventSourcing\TestCountingEvent;

class TestCountingCommand implements Command {

    /** @var int */
    private $number;

    public function __construct(int $number) {
        $this->number = $number;
    }

    public function execute(EventStore $eventStore) {
        $eventStore->storeEvent(new TestCountingEvent($this->number));
    }
}