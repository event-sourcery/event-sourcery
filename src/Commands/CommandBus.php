<?php namespace EventSourcery\EventSourcery\Commands;

interface CommandBus {
    public function execute(Command $command);
}