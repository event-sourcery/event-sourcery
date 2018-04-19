<?php namespace spec\EventSourcery\PersonalData;

use function EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class Pbkdf2KeyGeneratorSpec extends ObjectBehavior {

    function it_generates_keys() {
        $key1 = $this->generate()->getWrappedObject();
        $key2 = $this->generate()->getWrappedObject();
        $key3 = $this->generate()->getWrappedObject();

        expect($key1)->shouldNotBe($key2);
        expect($key2)->shouldNotBe($key3);
        expect($key3)->shouldNotBe($key1);
    }
}
