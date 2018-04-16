<?php namespace EventSourcery\CommandDispatch;

interface CommandHandler {
    public function handle($command);
}
