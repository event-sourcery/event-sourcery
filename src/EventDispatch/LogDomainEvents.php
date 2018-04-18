<?php namespace EventSourcery\EventDispatch;

use EventSourcery\EventSourcing\DomainEvent;

class LogDomainEvents implements Listener {

    public function handle(DomainEvent $event): void {
        $logData = get_class($event) . ': ' . json_encode($event->serialize()) . "\n\n";
        file_put_contents(storage_path('logs/domain-events.log'), $logData, FILE_APPEND);
    }
}