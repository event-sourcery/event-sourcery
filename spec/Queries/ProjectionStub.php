<?php namespace spec\EventSourcery\Queries;


use EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\Queries\Projection;

class ProjectionStub implements Projection {

    private $resetCount = 0;
    private $handleCount = 0;

    public function name() : string {
        return "test projection";
    }

    public function handle(DomainEvent $event) : void {
        $this->handleCount++;
    }

    public function reset() : void {
        $this->resetCount++;
    }

    public function resetCount(): int {
        return $this->resetCount;
    }

    public function handleCount(): int {
        return $this->handleCount;
    }
}