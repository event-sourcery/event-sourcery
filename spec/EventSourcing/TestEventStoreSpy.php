<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcing\DomainEvents;
use EventSourcery\EventSourcing\EventStore;
use EventSourcery\EventSourcing\StreamEvent;
use EventSourcery\EventSourcing\StreamEvents;
use EventSourcery\EventSourcing\StreamId;
use EventSourcery\EventSourcing\StreamVersion;

class TestEventStoreSpy implements EventStore {

    private $events = [];

    public function storeStream(StreamEvents $events): void {
        $this->events = array_merge($this->events,
            $events->map(function (StreamEvent $streamEvent) {
                return $streamEvent->event();
            })->toArray()
        );
    }

    public function storeEvent(DomainEvent $event): void {
        $this->events[] = $event;
    }

    public function getStream(StreamId $id): StreamEvents {
        $streamEvents = [];
        for ($i = 0; $i<count($this->events); $i++) {
            $streamEvents[] = new StreamEvent(
                $id,
                StreamVersion::fromInt($i+1),
                $this->events[$i]
            );
        }
        return StreamEvents::make($streamEvents);
    }

    public function storedEvents(): DomainEvents {
        return DomainEvents::make($this->events);
    }

    public function getEvents($take = 0, $skip = 0): DomainEvents {

    }
}