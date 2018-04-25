<?php namespace EventSourcery\EventSourcing;

class UuidId extends Id {
    public static function generate(): Id {
        return new static(Uuid::uuid4()->toString());
    }
}