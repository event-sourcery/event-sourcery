<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class CryptographicDetails implements SerializableValue {

    private $key;
    private $iv;

    public function serialize(): string {
        return json_encode([
            'key' => $this->key->serialize(),
            'iv'  => $this->iv->serialize(),
        ]);
    }

    public static function deserialize(string $string) {
        $values = json_decode($string);

        $key = EncryptionKey::deserialize($values->key);
        $iv  = InitializationVector::deserialize($values->iv);

        return new static($key, $iv);
    }

    public function __construct(EncryptionKey $key, InitializationVector $iv) {
        $this->key = $key;
        $this->iv  = $iv;
    }

    public function key(): EncryptionKey {
        return $this->key;
    }

    public function iv(): InitializationVector {
        return $this->iv;
    }
}