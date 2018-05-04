<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\PersonalData\PersonalKey;
use EventSourcery\EventSourcery\PersonalData\SerializablePersonalDataValue;

class PersonalDataValueObjectStub implements SerializablePersonalDataValue {

    /** @var PersonalKey */
    private $personalKey;
    /** @var string */
    public $string1;
    /** @var int */
    public $integer1;
    /** @var string */
    public $string2;
    /** @var int */
    public $integer2;

    public function __construct(PersonalKey $personalKey, string $string1, int $integer1, string $string2, int $integer2) {
        $this->personalKey = $personalKey;
        $this->string1 = $string1;
        $this->integer1 = $integer1;
        $this->string2 = $string2;
        $this->integer2 = $integer2;
    }

    public function serialize(): string {
        return json_encode([
            'personalKey' => $this->personalKey->serialize(),
            'string1' => $this->string1,
            'integer1' => $this->integer1,
            'string2' => $this->string2,
            'integer2' => $this->integer2,
        ]);
    }

    public static function deserialize(string $string) {
        $values = json_decode($string);
        return new static(PersonalKey::deserialize($values->personalKey), $values->string1, $values->integer1, $values->string2, $values->integer2);
    }

    public function personalKey(): PersonalKey {
        return $this->personalKey;
    }

    public function string1(): string {
        return $this->string1;
    }

    public function integer1(): int {
        return $this->integer1;
    }

    public function string2(): string {
        return $this->string2;
    }

    public function integer2(): int {
        return $this->integer2;
    }
}