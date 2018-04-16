<?php namespace spec\EventSourcery\EventSourcing;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProjectionsSpec extends ObjectBehavior {

    function it_can_hold_domain_events() {
        $this->beConstructedThrough('make', [[new TestProjection(), new TestProjection()]]);
        $this->count()->shouldBe(2);
    }

    function it_cant_hold_anything_else() {
        $this->beConstructedThrough('make', [[new TestListener()]]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
