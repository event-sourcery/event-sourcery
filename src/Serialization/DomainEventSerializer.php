<?php namespace EventSourcery\EventSourcery\Serialization;

interface DomainEventSerializer {
    function serialize(DomainEvent $event): array;
    function deserialize(array $serialized): DomainEvent;
    function eventNameForClass(string $className): string;
    function classNameForEvent(string $eventName): string;
}
