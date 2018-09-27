<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class NestedValueObjectsEventStub implements DomainEvent {

    /** @var NestedValueObjectsStub */
    public $vo;

    public function __construct(NestedValueObjectsStub $vo) {
        $this->vo = $vo;
    }
}