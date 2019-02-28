<?php namespace spec\EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\EventSourcing\DomainEventClassMap;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DomainEventClassMapSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType(DomainEventClassMap::class);
    }

    function it_can_translate_event_names_to_class_names() {
        $this->add('eventname', 'classname');
        $this->classNameForEvent('eventname')->shouldBe('classname');
    }

    function it_can_translate_class_names_to_event_names() {
        $this->add('eventname', 'classname');
        $this->eventNameForClass('classname')->shouldBe('eventname');
    }

    function it_can_provide_a_dictionary_of_all_mappings() {
        $this->add('eventname', 'classname');
        $this->add('eventname2', 'classname2');

        $this->toArray()->shouldBe([
            'eventname' => 'classname',
            'eventname2' => 'classname2',
        ]);
    }
}
