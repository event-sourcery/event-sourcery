<?php namespace EventSourcery\EventSourcing;

use EventSourcery\Collections\TypedCollection;

class DomainEvents extends TypedCollection {

    protected $collectionType = DomainEvent::class;
}