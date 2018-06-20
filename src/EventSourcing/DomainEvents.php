<?php namespace EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\Collections\TypedCollection;

/**
 * DomainEvents is a typed collection object that only contains
 * instances of type DomainEvent.
 */
class DomainEvents extends TypedCollection {
    protected $collectionType = DomainEvent::class;
}