<?php namespace spec\EventSourcery\EventDispatch;

use EventSourcery\EventDispatch\LogDomainEvents;
use PhpSpec\ObjectBehavior;

class LogDomainEventsSpec extends ObjectBehavior {

    function it_logs_domain_events_to_file() {
        $this->shouldHaveType(LogDomainEvents::class);
    }
}
