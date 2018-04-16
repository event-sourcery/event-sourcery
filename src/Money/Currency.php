<?php namespace EventSourcery\Money;

class Currency {
    /** @var string */
    private $type;

    public function __construct($type) {
        $this->type = strtoupper($type);
    }

    public function equals(Currency $that) {
        return $this->type == $that->type;
    }

    public function toString() {
        return $this->type;
    }

    public function __toString() {
        return $this->toString();
    }
}