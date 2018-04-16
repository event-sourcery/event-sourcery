<?php namespace EventSourcery\EventSourcing;

interface DomainEvent {

    public function serialize() : array;

    public static function deserialize(array $data) : DomainEvent;
}
