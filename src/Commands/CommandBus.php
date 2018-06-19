<?php namespace EventSourcery\EventSourcery\Commands;

interface CommandBus {

    /**
     * execute a command
     *
     * @param Command $command
     */
    public function execute(Command $command): void;
}