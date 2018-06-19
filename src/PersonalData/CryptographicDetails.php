<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

class CryptographicDetails implements SerializableValue {

    /**
     * a key => value array of details
     * @var array
     */
    private $details;
    /**
     * the name of the encryption type used
     * @var string
     */
    private $type;

    public function __construct(string $type, array $details) {
        $this->type = $type;
        $this->details = $details;
    }

    /**
     * get the type of encryption that these details were created for
     *
     * @return string
     */
    public function type(): string {
        return $this->type;
    }

    /**
     * get the value for a key from the cryptographic details
     *
     * @param $name
     * @return string
     * @throws CryptographicDetailsDoNotContainKey
     */
    public function key($name): string {
        if ( ! isset($this->details[$name])) {
            throw new CryptographicDetailsDoNotContainKey($name);
        }
        return $this->details[$name];
    }

    // serialization methods
    public function serialize(): string {
        return json_encode($this->details);
    }

    public static function deserialize(string $string) {
        return new static($string);
    }
}