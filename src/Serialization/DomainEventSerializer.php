<?php namespace EventSourcery\Serialization;

interface DomainEventSerializer {
    function serialize(DomainEvent $event): array;
    function deserialize(array $serialized): DomainEvent;
}