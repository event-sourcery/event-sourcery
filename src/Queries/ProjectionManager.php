<?php namespace EventSourcery\Queries;

use EventSourcery\Collections\Collection;
use EventSourcery\EventDispatch\Listener;
use EventSourcery\EventSourcing\DomainEvent;

class ProjectionManager implements Listener {

    /** @var Projections */
    private $projections;

    public function __construct(Projections $projections) {
        $this->projections = $projections;
    }

    public function add(Projection $projection) {
        $this->projections = $this->projections->add($projection);
    }

    public function get(string $name): Projection {
        return $this->projections->filter(function (Projection $projection) use ($name) {
            return $projection->name() === $name;
        })->first();
    }

    public function list() : Collection {
        return $this->projections->map(function (Projection $projection) {
            return $projection->name();
        });
    }

    public function all() : Projections {
        return $this->projections;
    }

    public function resetAll() : void {
        foreach ($this->projections as $projection) {
            $projection->reset();
        }
    }

    public function handle(DomainEvent $event) : void {
        $this->projections->each(function (Projection $projection) use ($event) {
            $projection->handle($event);
        });
    }
}