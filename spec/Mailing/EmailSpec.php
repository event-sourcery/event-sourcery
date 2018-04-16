<?php namespace spec\EventSourcery\Mailing;

use EventSourcery\Mailing\Email;
use PhpSpec\ObjectBehavior;

class EmailSpec extends ObjectBehavior {

    function it_represents_valid_email_addresses() {
        $this->beConstructedThrough('fromString', ['test@email.com']);
        $this->shouldHaveType(Email::class);
    }

    function it_rejects_invalid_email_addresses() {
        $this->beConstructedThrough('fromString', ['testcom']);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_compares_against_other_email_addresses() {
        $this->beConstructedThrough('fromString', ['test@email.com']);
        $this->shouldEqualValue(Email::fromString('test@email.com'));
        $this->shouldNotEqualValue(Email::fromString('another@email.com'));
    }

    function it_can_be_represented_as_a_string() {
        $this->beConstructedThrough('fromString', ['test@email.com']);
        $this->toString()->shouldBeString();
        $this->toString()->shouldBe('test@email.com');
    }
}
