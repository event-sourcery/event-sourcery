<?php namespace spec\EventSourcery\Commands;

use EventSourcery\Commands\Command;

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