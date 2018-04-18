<?php namespace EventSourcery\EventSourcing;

interface Timestamp {
    public static function fromString($timeString, $timeZone): Timestamp;
    public function toMysqlDateTime(): string;
}