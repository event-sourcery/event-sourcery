<?php namespace EventSourcery\EventSourcery\EventSourcing;

final class CanNotBuildAggregate extends \Exception
{
    public static function fromEmptyStream(string $aggregateType): self
    {
        return new CanNotBuildAggregate("Can not build aggregate of type {$aggregateType} from an empty stream.");
    }
}