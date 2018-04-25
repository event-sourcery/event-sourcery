<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;
use Ramsey\Uuid\Uuid;

class EncryptionKey implements SerializableValue {

    public static function generate(): EncryptionKey {
        return new static(hash_pbkdf2('sha256', Uuid::uuid4()->toString(), openssl_random_pseudo_bytes(16), 1000, 20));
    }

    public function serialize(): string {
        return base64_encode($this->key);
    }

    public static function deserialize($json) {
        return new static(base64_decode($json));
    }

    private $key;

    private function __construct($key) {
        $this->key = $key;
    }
}