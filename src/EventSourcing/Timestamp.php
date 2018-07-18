<?php namespace EventSourcery\EventSourcery\EventSourcing;

/**
 * A Timestamp is an internally used representation of a moment in time.
 */
class Timestamp {

    /** @var \DateTimeImmutable */
    private $dateTime;

    private function __construct(\DateTimeImmutable $dateTime) {
        $this->dateTime = $dateTime;
    }

    /**
     * now() creates a new timestamp for the current moment based
     * on the server's php datetime configuration
     *
     * @return Timestamp
     * @throws \Exception
     */
    public static function now(): Timestamp {
        return new static(new \DateTimeImmutable());
    }

    /**
     * construct a timestamp from string using the system timezone
     *
     * @param $timeString
     * @return Timestamp
     * @throws \Exception
     */
    public static function fromString($timeString): Timestamp {
        return new static(new \DateTimeImmutable($timeString));
    }

    /**
     * construct a timestamp from string including timezone
     *
     * @param $timeString
     * @param $timeZone
     * @return Timestamp
     * @throws \Exception
     */
    public static function fromStringWithTimezone($timeString, $timeZone): Timestamp {
        return new static(new \DateTimeImmutable($timeString, new \DateTimeZone($timeZone)));
    }

    /**
     * return a string in mysql date time format
     *
     * @return string
     */
    public function toMysqlDateTime(): string {
        return $this->dateTime->format('Y-m-d H:i:s');
    }
}
