<?php namespace spec\EventSourcery\Serialization;

use EventSourcery\EventSourcing\DomainEvent;

class ValueObjectEventStub implements DomainEvent {

    /** @var ValueObject */
    public $vo;

    public function __construct(ValueObject $vo) {
        $this->vo = $vo;
    }
}