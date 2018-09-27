<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

class InvalidNestedValueObjectsEventStub implements DomainEvent {

    /** @var InvalidNestedValueObjectsStub */
    public $vo;

    public function __construct(InvalidNestedValueObjectsStub $vo) {
        $this->vo = $vo;
    }
}