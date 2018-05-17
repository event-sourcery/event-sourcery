<?php namespace EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

interface DomainEventSerializer {
    function serialize(DomainEvent $event): string;
    function deserialize(array $serialized): DomainEvent;
    function eventNameForClass(string $className): string;
    function classNameForEvent(string $eventName): string;
}
