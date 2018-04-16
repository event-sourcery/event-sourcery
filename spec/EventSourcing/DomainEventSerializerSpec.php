<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\DomainEventClassMap;
use PhpSpec\ObjectBehavior;

class DomainEventSerializerSpec extends ObjectBehavior {

    function let() {
        $classMap = new DomainEventClassMap();
        $this->beConstructedWith($classMap);

        $classMap->add('TestDomainEvent', TestDomainEvent::class);
    }

    function it_can_serialize_events() {
        $this->serialize(new TestDomainEvent(12))
            ->shouldReturn('{"number":12}');
    }

    function it_can_deserialize_events() {
        $this->deserialize((object) [
            'event_name' => 'TestDomainEvent',
            'event_data' => ['number' => 6]
        ])->shouldContainEvent(
            new TestDomainEvent(6)
        );
    }

    function it_can_give_the_name_from_an_event_class() {
        $this->eventNameForClass(TestDomainEvent::class)->shouldBe('TestDomainEvent');
    }

    function it_can_give_the_class_from_an_event_name() {
        $this->classNameForEvent('TestDomainEvent')->shouldBe(TestDomainEvent::class);
    }
}
