<?php namespace EventSourcery\EventSourcery\EventSourcing;

/**
 * MagicAggregate is a default (yet optional) implementation
 * of an aggregate that creates a simplified developer
 * interface with which to implement aggregates.
 */
abstract class MagicAggregate implements Aggregate {

    /** @var StreamEvents */
    private $streamEvents;
    /** @var StreamVersion */
    private $version;

    protected function __construct() {
        $this->streamEvents = StreamEvents::make();
        $this->version      = StreamVersion::zero();
    }

    /**
     * returns the Id of the aggregate
     *
     * @return StreamId
     */
    abstract public function aggregateId(): StreamId;

    /**
     * return the version of the aggregate
     *
     * @return StreamVersion
     */
    public function aggregateVersion(): StreamVersion {
        return $this->version;
    }

    /**
     * raise an event from within the aggregate. this applies
     * the event to the aggregate state and stores it in the
     * pending stream events collection awaiting flush.
     *
     * @param DomainEvent $event
     */
    protected function raise(DomainEvent $event): void {
        $this->apply($event);
        $this->streamEvents = $this->streamEvents->add(
            new StreamEvent($this->aggregateId(), $this->aggregateVersion(), $event)
        );
    }

    /**
     * returns and clears the internal pending stream events.
     * this DOES violate CQS, however it's important not to
     * retrieve or store these events multiple times. pass these
     * directly into the event store.
     *
     * @return StreamEvents
     */
    public function flushEvents(): StreamEvents {
        $events = $this->streamEvents->copy();

        $this->streamEvents = StreamEvents::make();

        return $events;
    }

    /**
     * reconstruct the aggregate state from domain events
     *
     * @param StreamEvents $events
     * @return Aggregate
     */
    public static function buildFrom(StreamEvents $events): Aggregate {
        $aggregate = new static;
        $events->each(function (StreamEvent $event) use ($aggregate) {
            $aggregate->apply($event->event());
            if ( ! $event->version()->equals($aggregate->aggregateVersion())) {
                throw new UnexpectedAggregateVersionWhenBuildingFromEvents("When building from stream events aggregate {$aggregate->aggregateId()->toString()} expected version {$event->version()->toInt()} but got {$aggregate->aggregateVersion()->toInt()}.");
            }
        });
        return $aggregate;
    }

    /**
     * apply domain events to aggregate state by mapping the class
     * name to method name using strings
     *
     * @param DomainEvent $event
     */
    protected function apply(DomainEvent $event): void {
        $eventName = explode('\\', get_class($event));

        $method = 'apply' . $eventName[count($eventName) - 1];

        if (method_exists($this, $method)) {
            $this->$method($event);
            $this->version = $this->version->next();
        }
    }
}
