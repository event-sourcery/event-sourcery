<?php namespace spec\EventSourcery\CommandDispatch;

use EventSourcery\CommandDispatch\Command;

class ArgumentCommandStub implements Command {

    public function execute(ResolutionTargetStub $dependency) {
        $this->resolved = $dependency;
    }

    public function getResolved() {
        return $this->resolved;
    }
}