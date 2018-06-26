<?php namespace spec\EventSourcery\EventSourcery\Commands;

use EventSourcery\EventSourcery\Commands\Command;
use EventSourcery\EventSourcery\EventSourcing\EventStore;

class EmptyArgumentCommandStub implements Command {

    private $a;
    private $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }

    public function execute(EventStore $store) {

    }
}