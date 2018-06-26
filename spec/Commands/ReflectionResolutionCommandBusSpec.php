<?php namespace spec\EventSourcery\EventSourcery\Commands;

use EventSourcery\EventSourcery\EventSourcing\EventStore;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;
use spec\EventSourcery\EventSourcery\EventSourcing\TestEventStoreSpy;

class ReflectionResolutionCommandBusSpec extends ObjectBehavior {

    private $container;

    function let() {
        $this->container = new ContainerStub();
        $this->container->set(EventStore::class, new TestEventStoreSpy());

        $this->beConstructedWith($this->container);
    }

    function it_executes_with_no_arguments() {
        $this->container->set(ResolutionTargetStub::class, new ResolutionTargetStub(666));
        $this->execute(new EmptyArgumentCommandStub('abc', 123))->shouldReturn(null);
    }

    function it_executes_with_argument_resolution() {
        $this->container->set(ResolutionTargetStub::class, new ResolutionTargetStub(666));
        $command = new ArgumentCommandStub('abc', 123);
        $this->execute($command)->shouldReturn(null);
        expect($command->getResolved())->shouldHaveType(ResolutionTargetStub::class);
    }
}
