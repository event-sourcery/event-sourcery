<?php namespace EventSourcery\PhpSpec;

use EventSourcery\EventSourcing\DomainEvents;
use PhpSpec\ObjectBehavior;

class ContainEventsSpec extends ObjectBehavior {

    public function it_checks_if_a_collection_contains_a_matching_event() {
        $events = DomainEvents::make([
            new EventStub(1, 2, 3),
        ]);

        expect($events)->shouldContainEvent(
            new EventStub(1, 2, 3)
        );

        expect($events)->shouldNotContainEvent(
            new EventStub(1, 2, 2)
        );
    }

    // these tests aren't particularly good, actually test the classes
    // when you get time
    public function it_fails_if_a_collection_doesnt_have_a_matching_event() {
        $events = DomainEvents::make([]);

        expect($events)->shouldNotContainEvent(
            new EventStub(3, 2, 1)
        );

        $events = DomainEvents::make([
            new EventStub(3, 2, 1),
        ]);

        expect($events)->shouldNotContainEvent(
            new EventStub(4, 4, 4)
        );
    }

    public function it_can_check_multiple_events() {
        $events = DomainEvents::make([
            new EventStub(1, 2, 3),
            new EventStub(4, 4, 4)
        ]);

        expect($events)->shouldContainEvents([
            new EventStub(1, 2, 3),
            new EventStub(4, 4, 4)
        ]);

        expect($events)->shouldNotContainEvent(
            new EventStub(1, 2, 2)
        );
    }

    public function it_compares_elements_in_arrays() {
        $events = DomainEvents::make([
            new EventStub(1, 2, [3, 2, 1]),
            new EventStub(4, 4, 4)
        ]);

        expect($events)->shouldContainEvents([
            new EventStub(1, 2, [3, 2, 1])
        ]);

        expect($events)->shouldContainEvents([
            new EventStub(4, 4, 4)
        ]);

        expect($events)->shouldNotContainEvents([
            new EventStub(1, 2, [3, 2, 2]),
            new EventStub(4, 4, 5),
        ]);
    }
}