<?php namespace spec\EventSourcery\Mailing;

use EventSourcery\Mailing\Email;
use EventSourcery\Mailing\Mail;
use PhpSpec\ObjectBehavior;

class MailSpec extends ObjectBehavior {
    private $fromName = 'from name';
    private $fromEmail = 'fromEmail@email.com';
    private $toEmail = 'toEmail@email.com';
    private $subject = 'subject';
    private $html = 'html';
    private $serialized = [
        'From' => 'from name <fromEmail@email.com>',
        'To' => 'toEmail@email.com',
        'Subject' => 'subject',
        'HtmlBody' => 'html',
    ];

    function let() {
        $this->beConstructedWith(
            $this->fromName,
            Email::fromString($this->fromEmail),
            Email::fromString($this->toEmail),
            $this->subject,
            $this->html
        );
    }

    function it_can_be_initialized() {
        $this->shouldHaveType(Mail::class);
    }

    function it_can_be_serialized() {
        foreach ($this->serialized as $key => $value) {
            $this->serialize()->shouldHaveKeyWithValue($key, $value);
        }
    }
}