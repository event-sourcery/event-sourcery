<?php namespace EventSourcery\EventSourcery\StreamProcessing;

use EventSourcery\EventSourcery\Collections\Collection;
use EventSourcery\EventSourcery\EventDispatch\Listener;
use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

/**
 * ProjectionManager is a tool to provide some basic helpers
 * with which to interact with projections. Its usage is
 * entirely optional.
 */
class ProjectionManager implements Listener {

    /** @var Projections */
    private $projections;

    public function __construct(Projections $projections) {
        $this->projections = $projections;
    }

    /**
     * add a projection to the projection manager
     *
     * @param Projection $projection
     */
    public function add(Projection $projection) {
        $this->projections = $this->projections->add($projection);
    }

    /**
     * retrieve a projection based on its string name
     *
     * @param string $name
     * @return Projection
     */
    public function get(string $name): Projection {
        $projections = $this->projections->filter(function (Projection $projection) use ($name) {
            return $projection->name() === $name;
        });
        
        if ($projections->count() == 0) {
            throw new CanNotFindProjection($name);
        }
        
        return $projections->first();
    }

    /**
     * retrieve a list of projection names
     *
     * @return Collection
     */
    public function listNames() : Collection {
        return $this->projections->map(function (Projection $projection) {
            return $projection->name();
        });
    }

    /**
     * return a collection of all projections in the manager
     *
     * @return Projections
     */
    public function all() : Projections {
        return $this->projections;
    }

    /**
     * reset ALL projections (crazy to do outside of development)
     */
    public function resetAll() : void {
        foreach ($this->projections as $projection) {
            $projection->reset();
        }
    }

    /**
     * receive a domain event and hand it off to every projection
     *
     * @param DomainEvent $event
     */
    public function handle(DomainEvent $event) : void {
        $this->projections->each(function (Projection $projection) use ($event) {
            $projection->handle($event);
        });
    }
}