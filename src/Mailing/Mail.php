<?php namespace EventSourcery\Mailing;

class Mail {

    private $fromName;
    private $fromEmail;
    private $toEmail;
    private $subject;
    private $html;

    public function __construct(string $fromName, Email $fromEmail, Email $toEmail, string $subject, string $html) {
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->html = $html;
    }
    
    public function serialize(): array {
        return [
            'From' => $this->fromName . ' <' . $this->fromEmail->toString() . '>',
            'To' => $this->toEmail->toString(),
            'Subject' => $this->subject,
            'HtmlBody' => $this->html,
        ];
    }
}