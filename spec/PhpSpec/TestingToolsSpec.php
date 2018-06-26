<?php namespace spec\EventSourcery\EventSourcery\PhpSpec;

use EventSourcery\EventSourcery\EventSourcing\StreamId;
use spec\EventSourcery\EventSourcery\Commands\ResolutionTargetStub;
use spec\EventSourcery\EventSourcery\Commands\Stubs\TestCountingCommand;
use spec\EventSourcery\EventSourcery\EventSourcing\TestCountingEvent;

class TestingToolsSpec extends EventSourcedBehavior {

    function let() {
        $this->setup();
    }

    function it_can_be_tested() {
        $this->container->set(ResolutionTargetStub::class, new ResolutionTargetStub(5));
        $this->classMap->add('TestCountingEvent', TestCountingEvent::class);

        $this->given(
            StreamId::fromString('id'),
            new TestCountingEvent(1),
            new TestCountingEvent(2)
        )->when(
            new TestCountingCommand(3)
        )->then(
            new TestCountingEvent(3)
        );
    }
}