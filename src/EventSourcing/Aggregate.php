<?php namespace EventSourcery\EventSourcery\EventSourcing;

/**
 * An aggregate is an immediately consistent transactional barrier
 * that allows raising of events that must conform to invariants.
 *
 * In clearer terms, you might use an aggregate when an event must
 * be raised that requires state from previous events in order to
 * make some kind of business rule validation check.
 *
 * Interface Aggregate
 * @package EventSourcery\EventSourcery\EventSourcing
 */
interface Aggregate {

    /**
     * returns the Id of the aggregate
     *
     * @return StreamId
     */
    function aggregateId(): StreamId;

    /**
     * return the version of the aggregate
     *
     * @return StreamVersion
     */
    function aggregateVersion() : StreamVersion;

    /**
     * returns and clears the internal pending stream events.
     * this DOES violate CQS, however it's important not to
     * retrieve or store these events multiple times. pass these
     * directly into the event store.
     *
     * @return StreamEvents
     */
    function flushEvents(): StreamEvents;

    /**
     * reconstruct the aggregate state from domain events
     *
     * @param StreamEvents $events
     * @return Aggregate
     */
    static function buildFrom(StreamEvents $events): Aggregate;
}