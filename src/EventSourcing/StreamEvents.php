<?php namespace EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\Collections\TypedCollection;

/**
 * StreamEvents is a typed collection object that only contains
 * instances of type StreamEvent.
 */
final class StreamEvents extends TypedCollection {

    protected $collectionType = StreamEvent::class;

    /**
     * return a DomainEvents collection containing the domain
     * events from within this stream events collection
     *
     * @return DomainEvents
     */
    public function toDomainEvents(): DomainEvents {
        return DomainEvents::make($this->map(function (StreamEvent $streamEvent) {
            return $streamEvent->event();
        })->toArray());
    }
}