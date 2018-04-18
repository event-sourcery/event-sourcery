<?php namespace spec\EventSourcery\Commands;

use EventSourcery\Commands\Command;

class ArgumentCommandStub implements Command {

    public function execute(ResolutionTargetStub $dependency) {
        $this->resolved = $dependency;
    }

    public function getResolved() {
        return $this->resolved;
    }
}