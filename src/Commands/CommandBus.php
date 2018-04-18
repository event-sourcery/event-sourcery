<?php namespace EventSourcery\Commands;

interface CommandBus {
    public function execute(Command $command);
}