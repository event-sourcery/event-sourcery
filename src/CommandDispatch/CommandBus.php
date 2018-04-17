<?php namespace EventSourcery\CommandDispatch;

interface CommandBus {
    public function execute(Command $command);
}