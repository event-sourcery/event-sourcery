<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class ValueObjectEventStub implements DomainEvent {

    /** @var ValueObjectStub */
    public $vo;

    public function __construct(ValueObjectStub $vo) {
        $this->vo = $vo;
    }
}