<?php namespace EventSourcery\EventSourcery\EventSourcing;

/**
 * An EventStore is the storage mechanism into which
 * domain events are serialized and from which are deserialized.
 *
 * Interface EventStore
 * @package EventSourcery\EventSourcery\EventSourcing
 */
interface EventStore {

    /**
     * persist events in an event stream
     *
     * @param StreamEvents $events
     */
    public function storeStream(StreamEvents $events): void;

    /**
     * persist a single event
     *
     * @param DomainEvent $event
     */
    public function storeEvent(DomainEvent $event): void;

    /**
     * retrieve an event stream based on its id
     *
     * @param StreamId $id
     * @return StreamEvents
     */
    public function getStream(StreamId $id): StreamEvents;

    /**
     * a pagination function for processing events by pages
     * 0 is the first event in the store
     *
     * @param int $take
     * @param int $skip
     * @return DomainEvents
     */
    public function getEvents($take = 0, $skip = 0): DomainEvents;
}
