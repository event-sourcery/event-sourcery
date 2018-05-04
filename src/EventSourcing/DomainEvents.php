<?php namespace EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\Collections\TypedCollection;

class DomainEvents extends TypedCollection {

    protected $collectionType = DomainEvent::class;
}