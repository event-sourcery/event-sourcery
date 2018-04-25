<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class InitializationVector implements SerializableValue {

    private $iv;

    public function serialize(): string {
        return base64_encode($this->iv);
    }

    public function toBinary() {
        return $this->iv;
    }

    public static function deserialize($json) {
        return new static(base64_decode($json));
    }

    public static function generate(): InitializationVector {
        $isSafe = false;

        do {
            $iv = openssl_random_pseudo_bytes(16, $isSafe);
        } while ( ! $isSafe);

        return new static($iv);
    }

    private function __construct($iv) {
        $this->iv = $iv;
    }
}