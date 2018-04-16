<?php namespace EventSourcery\EventSourcing;

interface DomainEventSerializer {
    function serialize(DomainEvent $event): array;
    function deserialize(array $serialized): DomainEvent;
}