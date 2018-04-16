<?php namespace spec\EventSourcery\Mailing;

use EventSourcery\Mailing\SendMailViaPostmark;
use PhpSpec\ObjectBehavior;

class SendMailViaPostmarkSpec extends ObjectBehavior {

    function it_can_be_initialized() {
        $this->beConstructedWith('postmark token');
        $this->shouldHaveType(SendMailViaPostmark::class);
    }
}