<?php namespace EventSourcery\PersonalData;

use Ramsey\Uuid\Uuid;

class Pbkdf2KeyGenerator implements EncryptionKeyGenerator {

    function generate() {
        return hash_pbkdf2('sha256', Uuid::uuid4()->toString(), openssl_random_pseudo_bytes(16), 1000, 20);
    }
}