<?php namespace EventSourcery\EventSourcing\Serialization;

interface DomainEventSerializer {
    function serialize(DomainEvent $event): array;
    function deserialize(array $serialized): DomainEvent;
}