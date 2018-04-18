<?php namespace EventSourcery\EventSourcing;

use EventSourcery\Collections\TypedCollection;

final class StreamEvents extends TypedCollection {

    protected $collectionType = StreamEvent::class;

    public function toDomainEvents() {
        return DomainEvents::make($this->map(function (StreamEvent $streamEvent) {
            return $streamEvent->event();
        })->toArray());
    }
}