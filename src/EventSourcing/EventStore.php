<?php namespace EventSourcery\EventSourcing;

interface EventStore {

    public function storeStream(StreamEvents $events): void;
    public function storeEvent(DomainEvent $event): void;
    public function getStream(StreamId $id): StreamEvents;
    public function getEvents($take = 0, $skip = 0): DomainEvents;
}
