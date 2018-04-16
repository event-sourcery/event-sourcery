<?php namespace EventSourcery\Mailing;

class Email {

    /** @var string */
    private $address;

    protected function __construct(string $address) {
        $this->address = $address;
    }

    public static function fromString(string $address) {
        if (false === filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("$address is not a valid email address.");
        }
        return new Email($address);
    }

    public function equals(Email $that): bool {
        return $this->address === $that->address;
    }

    public function toString(): string {
        return $this->address;
    }
}
