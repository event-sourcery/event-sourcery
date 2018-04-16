<?php namespace EventSourcery\EventSourcing;

abstract class Aggregate {

    private $streamEvents;
    private $version;

    protected function __construct() {
        $this->streamEvents = StreamEvents::make();
        $this->version = StreamVersion::zero();
    }

    protected function raise(DomainEvent $event) : void {
        $this->apply($event);
        $this->streamEvents = $this->streamEvents->add(
            new StreamEvent($this->id(), $this->aggregateVersion(), $event)
        );
    }

    abstract public function id() : Id;

    public function flushEvents() : StreamEvents {
        $events = $this->streamEvents->copy();
        $this->streamEvents = StreamEvents::make();
        return $events;
    }

    public static function buildFrom(DomainEvents $events) : Aggregate {
        $aggregate = new static;
        $events->each(function (DomainEvent $event) use ($aggregate) {
            $aggregate->apply($event);
        });
        return $aggregate;
    }

    public function aggregateVersion() : StreamVersion {
        return $this->version;
    }

    protected function apply(DomainEvent $event) : void {
        // project event application
        $eventName = explode('\\', get_class($event));
        $method = 'apply' . $eventName[count($eventName) - 1];
        $this->$method($event);

        // increment version
        $this->version = $this->version->next();
    }
}
