<?php namespace EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

interface DomainEventSerializer {

    /**
     * serialize a domain event to a string
     *
     * @param DomainEvent $event
     * @return string
     */
    function serialize(DomainEvent $event): string;

    /**
     * deserialize a domain event from a string
     *
     * @param array $serialized
     * @return DomainEvent
     */
    function deserialize(array $serialized): DomainEvent;

    /**
     * get the event name for an event represented by a specific class
     *
     * @param string $className
     * @return string
     */
    function eventNameForClass(string $className): string;

    /**
     * get the class name for the representation of an event with a specific name
     *
     * @param string $eventName
     * @return string
     */
    function classNameForEvent(string $eventName): string;
}
