<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class StringEventStub implements DomainEvent {

    /** @var string */
    public $str;

    public function __construct(string $str) {
        $this->str = $str;
    }
}