<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\DomainEvents;
use PhpSpec\ObjectBehavior;
use spec\EventSourcery\Collections\DomainEventStub;

class DomainEventsSpec extends ObjectBehavior {

    function it_can_hold_domain_events() {
        $this->beConstructedThrough('make', [[new DomainEventStub(), new DomainEventStub()]]);
        $this->count()->shouldBe(2);
    }

    function it_cant_hold_anything_else() {
        $this->beConstructedThrough('make', [[DomainEvents::make()]]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
