<?php namespace EventSourcery\EventSourcing;

class DomainEventSerializer {

    /** @var DomainEventClassMap */
    private $eventClasses;

    public function __construct(DomainEventClassMap $eventClasses) {
        $this->eventClasses = $eventClasses;
    }

    public function serialize(DomainEvent $event): string {
        return json_encode($event->serialize());
    }

    public function deserialize(\stdClass $serializedEvent): DomainEvent {
        $class = $this->eventClasses->classNameForEvent($serializedEvent->event_name);
        return $class::deserialize($serializedEvent->event_data);
    }

    public function classNameForEvent($eventName): string {
        return $this->eventClasses->classNameForEvent($eventName);
    }

    public function eventNameForClass(string $className): string {
        return $this->eventClasses->eventNameForClass($className);
    }
}
