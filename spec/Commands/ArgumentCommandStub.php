<?php namespace spec\EventSourcery\EventSourcery\Commands;

use EventSourcery\EventSourcery\Commands\Command;

class ArgumentCommandStub implements Command {

    public function execute(ResolutionTargetStub $dependency) {
        $this->resolved = $dependency;
    }

    public function getResolved() {
        return $this->resolved;
    }
}