<?php namespace EventSourcery\EventSourcing;

class StreamEvent {

    private $id;
    private $version;
    private $event;

    public function __construct(StreamId $id, StreamVersion $version, DomainEvent $event) {
        $this->id = $id;
        $this->version = $version;
        $this->event = $event;
    }

    public function id() : StreamId {
        return $this->id;
    }

    public function version() : StreamVersion {
        return $this->version;
    }

    public function event() : DomainEvent {
        return $this->event;
    }
}