<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\DomainEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DomainEventsSpec extends ObjectBehavior {

    function it_can_hold_domain_events() {
        $this->beConstructedThrough('make', [[new TestDomainEvent(), new TestDomainEvent()]]);
        $this->count()->shouldBe(2);
    }

    function it_cant_hold_anything_else() {
        $this->beConstructedThrough('make', [[DomainEvents::make()]]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
