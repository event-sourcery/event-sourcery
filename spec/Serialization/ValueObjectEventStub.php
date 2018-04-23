<?php namespace spec\EventSourcery\Serialization;

use EventSourcery\EventSourcing\DomainEvent;

class ValueObjectEventStub implements DomainEvent {

    /** @var ValueObjectStub */
    public $vo;

    public function __construct(ValueObjectStub $vo) {
        $this->vo = $vo;
    }
}