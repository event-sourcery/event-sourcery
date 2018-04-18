<?php namespace spec\EventSourcery\Queries;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProjectionsSpec extends ObjectBehavior {

    function it_can_hold_domain_events() {
        $this->beConstructedThrough('make', [[new ProjectionStub(), new ProjectionStub()]]);
        $this->count()->shouldBe(2);
    }

    function it_cant_hold_anything_else() {
        $this->beConstructedThrough('make', [[new ListenerStub()]]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
