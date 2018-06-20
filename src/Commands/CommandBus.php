<?php namespace EventSourcery\EventSourcery\Commands;

/**
 * A CommandBus is a bus through which commands travel
 * toward their ultimate goal of being executed. It's possible
 * to decorate command bus implementations with other command
 * bus implementations in order to implement a sort of
 * middleware-like pattern.
 *
 * Interface CommandBus
 * @package EventSourcery\EventSourcery\Commands
 */
interface CommandBus {

    /**
     * execute a command
     *
     * @param Command $command
     */
    public function execute(Command $command): void;
}