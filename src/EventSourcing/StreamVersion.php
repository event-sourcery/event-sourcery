<?php namespace EventSourcery\EventSourcing;

class StreamVersion {

    private $version;

    private function __construct(int $version) {
        $this->version = $version;
    }

    public static function fromInt(int $version) : StreamVersion {
        return new StreamVersion($version);
    }

    public static function zero() : StreamVersion {
        return new StreamVersion(0);
    }

    public function next() : StreamVersion {
        return new StreamVersion($this->version + 1);
    }

    public function equals(StreamVersion $that) : bool {
        return $this->version === $that->version;
    }

    public function toInt() : int {
        return (string) $this->version;
    }
}