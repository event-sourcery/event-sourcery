<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class ArrayEventStub implements DomainEvent {

    /** @var array */
    public $array;

    public function __construct(array $array) {
        $this->array = $array;
    }
}