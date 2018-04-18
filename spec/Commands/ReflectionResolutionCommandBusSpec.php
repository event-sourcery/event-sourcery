<?php namespace spec\EventSourcery\Commands;

use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class ReflectionResolutionCommandBusSpec extends ObjectBehavior {

    private $container;

    function let() {
        $this->container = new ContainerStub();
        $this->beConstructedWith($this->container);
    }

    function it_executes_with_no_arguments() {
        $this->execute(new EmptyArgumentCommandStub('abc', 123))->shouldReturn(null);
    }

    function it_executes_with_argument_resolution() {
        $this->container->set(ResolutionTargetStub::class, new ResolutionTargetStub(666));
        $command = new ArgumentCommandStub('abc', 123);
        $this->execute($command)->shouldReturn(null);
        expect($command->getResolved())->shouldHaveType(ResolutionTargetStub::class);
    }
}
