<?php namespace EventSourcery\EventSourcery\EventSourcing;

/**
 * A StreamEvent is a domain event with additional information
 * about the stream that it belongs to and which version within
 * that stream the event represents.
 */
class StreamEvent {

    private $id;
    private $version;
    private $event;

    public function __construct(StreamId $id, StreamVersion $version, DomainEvent $event) {
        $this->id = $id;
        $this->version = $version;
        $this->event = $event;
    }

    /**
     * retrieve the id of the stream that this event belongs to
     *
     * @return StreamId
     */
    public function id() : StreamId {
        return $this->id;
    }

    /**
     * retrieve the version of the stream that this event represents
     *
     * @return StreamVersion
     */
    public function version() : StreamVersion {
        return $this->version;
    }

    /**
     * retrieve the domain event
     *
     * @return DomainEvent
     */
    public function event() : DomainEvent {
        return $this->event;
    }
}