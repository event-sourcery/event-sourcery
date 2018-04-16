<?php namespace EventSourcery\EventSourcing;

class DomainEvents extends TypedCollection {

    protected $collectionType = DomainEvent::class;
}