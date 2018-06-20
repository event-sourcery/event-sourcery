<?php namespace EventSourcery\EventSourcery\EventSourcing;

/**
 * A StreamVersion is the point in the history of a stream in
 * which a specific event has been applied.
 */
class StreamVersion {

    private $version;

    private function __construct(int $version) {
        $this->version = $version;
    }

    /**
     * return a stream version instance from integer
     *
     * @param int $version
     * @return StreamVersion
     */
    public static function fromInt(int $version) : StreamVersion {
        return new StreamVersion($version);
    }

    /**
     * create a new stream version instance for the beginning
     * of a newly created stream
     *
     * @return StreamVersion
     */
    public static function zero() : StreamVersion {
        return new StreamVersion(0);
    }

    /**
     * return a new stream version that is incremented by one
     * from the current version.
     *
     * @return StreamVersion
     */
    public function next() : StreamVersion {
        return new StreamVersion($this->version + 1);
    }

    /**
     * compare two stream versions for equality
     *
     * @param StreamVersion $that
     * @return bool
     */
    public function equals(StreamVersion $that) : bool {
        return $this->version === $that->version;
    }

    /**
     * cast the stream version to integer
     *
     * @return int
     */
    public function toInt() : int {
        return (string) $this->version;
    }
}