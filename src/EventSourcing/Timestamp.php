<?php namespace EventSourcery\EventSourcery\EventSourcing;

use DateTimeZone;
use DateTimeImmutable;
use EventSourcery\EventSourcery\Serialization\SerializableValue;

/**
 * A Timestamp is an internally used representation of a moment in time.
 */
class Timestamp implements SerializableValue
{

    /** @var DateTimeImmutable */
    private $dateTime;

    private function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * now() creates a new timestamp for the current moment based
     * on the server's php datetime configuration
     *
     * @return static
     * @throws \Exception
     */
    public static function now(): Timestamp
    {
        return new static(new DateTimeImmutable('now', new DateTimeZone('UTC')));
    }

    /**
     * construct a timestamp from string using the system timezone
     *
     * @param $timeString
     * @return static
     * @throws \Exception
     */
    public static function fromString($timeString): Timestamp
    {
        return new static(new DateTimeImmutable($timeString, new DateTimeZone('UTC')));
    }

    /**
     * construct a timestamp from string including timezone
     *
     * The time string can be any php format
     *
     * @param $timeString
     * @param $timeZone
     * @return static
     * @throws \Exception
     */
    public static function fromStringWithTimezone($timeString, $timeZone): Timestamp
    {
        return new static(new DateTimeImmutable($timeString, new DateTimeZone($timeZone)));
    }

    /**
     * return a string in mysql date time format
     *
     * @return string
     */
    public function toMysqlDateTime(): string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    /**
     * return a string in ISO8601 date time format (preferred date
     * time serialization format due to zone offset being included)
     *
     * @return string
     */
    public function toIso8601(): string
    {
        return $this->dateTime->format('c');
    }

    /**
     * serialize() returns an associated array form of the
     * value for persistence which will be deserialized when needed.
     *
     * the array should contain primitives for both keys and values.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'dateTime' => $this->toMysqlDateTime(),
            'timeZone' => $this->dateTime->getTimezone()->getName(),
        ];
    }

    public function toDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function format(string $format): string
    {
        return $this->dateTime->format($format);
    }

    public function equals(Timestamp $that): bool
    {
        return $this->toIso8601() === $that->toIso8601();
    }

    public function __toString(): string
    {
        return $this->toMysqlDateTime();
    }

    /**
     * deserialize() returns a value object from an associative array received
     * from persistence
     *
     * @param array $data
     * @return static
     * @throws \Exception
     */
    public static function deserialize(array $data)
    {
        return static::fromStringWithTimezone($data['dateTime'], $data['timeZone']);
    }
}
