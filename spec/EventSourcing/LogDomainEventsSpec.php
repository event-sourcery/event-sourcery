<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\LogDomainEvents;
use PhpSpec\ObjectBehavior;

class LogDomainEventsSpec extends ObjectBehavior {

    function it_logs_domain_events_to_file() {
        $this->shouldHaveType(LogDomainEvents::class);
    }
}
