<?php namespace EventSourcery\PersonalData;

use EventSourcery\Serialization\SerializableValue;

class CryptographicDetails implements SerializableValue {

    private const DELIMITER = '|||';

    private $key;
    private $iv;

    public function toString(): string {
        return $this->key->toString() . self::DELIMITER . $this->iv->toString();
    }

    public static function fromString($string) {
        list($keyString, $ivString) = explode(self::DELIMITER, $string);
        $key = EncryptionKey::fromString($keyString);
        $iv = InitializationVector::fromString($ivString);
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