<?php namespace EventSourcery\EventSourcery\EventSourcing;

/**
 * The DomainEventClassMap stores a list of domain event classes
 * and the 'event name' that they map to. This is used for
 * serialization to and from the event store.
 */
class DomainEventClassMap
{

    private $eventClasses = [];

    /**
     * add a mapping from an 'event name' to a fully qualified
     * class name
     *
     * @param $event
     * @param $class
     */
    public function add($event, $class): void
    {
        $this->eventClasses[$event] = $class;
    }

    /**
     * retrieve the fully qualified class name for an 'event name'
     *
     * @param $event
     * @return string
     */
    public function classNameForEvent($event): string
    {
        if ( ! isset($this->eventClasses[$event])) {
            throw new \InvalidArgumentException("Could not find a class for the event {$event}.");
        }
        return $this->eventClasses[$event];
    }

    /**
     * return the 'event name' for a fully qualified class name
     *
     * @param $class
     * @return string
     */
    public function eventNameForClass($class): string
    {
        $found = array_search($class, $this->eventClasses);
        if ( ! $found) {
            throw new \InvalidArgumentException("Could not find an event name for the class {$class}.");
        }
        return $found;
    }

    public function toArray(): array
    {
        return $this->eventClasses;
    }
}
