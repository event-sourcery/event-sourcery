<?php namespace EventSourcery\Mailing;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSingleEmail implements ShouldQueue {

    use InteractsWithQueue, Queueable;

    /** @var string */
    private $fromName;
    /** @var string */
    private $fromEmail;
    /** @var string */
    private $toEmail;
    /** @var string */
    private $subject;
    /** @var string */
    private $body;

    public function __construct($fromName, Email $fromEmail, Email $toEmail, $subject, $body) {
        $this->fromName  = $fromName;
        $this->fromEmail = $fromEmail->toString();
        $this->toEmail   = $toEmail->toString();
        $this->subject   = $subject;
        $this->body      = $body;
    }

    public function handle() {
        (new SendMailViaPostmark(env('POSTMARK_SERVER_TOKEN')))->single(
            new Mail($this->fromName, Email::fromString($this->fromEmail), Email::fromString($this->toEmail), $this->subject, $this->body)
        );
    }
}