<?php namespace EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

/**
 * CryptographicDetails is the value object that contains the
 * necessary details to encrypt and decrypt personal data for
 * a single person.
 */
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
    private $encryption;

    public function __construct(string $encryption, array $details) {
        $this->encryption = $encryption;
        $this->details    = $details;
    }

    /**
     * get the type of encryption that these details were created for
     *
     * @return string
     */
    public function encryption(): string {
        return $this->encryption;
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
        return json_encode($this->details + ['encryption' => $this->encryption]);
    }

    public static function deserialize(string $string) {
        $data = (array) json_decode($string);
        $encryption = $data['encryption'];
        unset($data['encryption']);

        return new static($encryption, $data);
    }
}