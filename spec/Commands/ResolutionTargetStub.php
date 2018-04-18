<?php namespace spec\EventSourcery\Commands;

class ResolutionTargetStub {

    private $number;

    public function __construct($number) {
        $this->number = $number;
    }

    public function number() {
        return $this->number;
    }
}