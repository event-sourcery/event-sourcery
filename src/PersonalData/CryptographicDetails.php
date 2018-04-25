<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class CryptographicDetails implements SerializableValue {

    private const DELIMITER = '|||';

    private $key;
    private $iv;

    public function serialize(): string {
        return $this->key->serialize() . self::DELIMITER . $this->iv->serialize();
    }

    public static function deserialize(string $string) {
        list($keyString, $ivString) = explode(self::DELIMITER, $string);
        $key = EncryptionKey::deserialize($keyString);
        $iv = InitializationVector::deserialize($ivString);
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