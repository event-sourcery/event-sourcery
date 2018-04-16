<?php namespace EventSourcery\EventSourcing;

class DomainEventClassMap {

    private $eventClasses;

    public function add($event, $class): void {
        $this->eventClasses[$event] = $class;
    }

    public function classNameForEvent($event): string {
        if ( ! isset($this->eventClasses[$event])) {
            throw new \InvalidArgumentException("Could not find a class for the event {$event}.");
        }
        return $this->eventClasses[$event];
    }

    public function eventNameForClass($class): string {
        $found = array_search($class, $this->eventClasses);
        if ( ! $found) {
            throw new \InvalidArgumentException("Could not find an event name for the class {$class}.");
        }
        return $found;
    }
}
