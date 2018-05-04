<?php namespace spec\EventSourcery\EventSourcery\Commands;

use EventSourcery\EventSourcery\Commands\Command;

class EmptyArgumentCommandStub implements Command {

    private $a;
    private $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }

    public function execute() {

    }
}