<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\PersonalData\PersonalKey;
use EventSourcery\EventSourcery\PersonalData\SerializablePersonalDataValue;

class PersonalDataValueObjectStub implements SerializablePersonalDataValue {

    /** @var PersonalKey */
    private $personalKey;
    /** @var bool */
    private $erased = false;
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

    /**
     * the factory method to build this data from erased state
     *
     * @param PersonalKey $personalKey
     * @return mixed
     */
    public static function fromErasedState(PersonalKey $personalKey) {
        $value = new static($personalKey, '', 0, '', 0);
        $value->erased = true;
        return value;
    }

    public function serialize(): array {
        return [
            'personalKey' => $this->personalKey->serialize(),
            'string1' => $this->string1,
            'integer1' => $this->integer1,
            'string2' => $this->string2,
            'integer2' => $this->integer2,
        ];
    }

    public static function deserialize(array $data) {
        return new static(PersonalKey::deserialize($data['personalKey']), $data['string1'], $data['integer1'], $data['string2'], $data['integer2']);
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

    /**
     * the wasErased method returns true if built fromErasedState.
     * due to the requirements for individual value objects, this must
     * be implemented manually
     *
     * @return bool
     */
    public function wasErased(): bool {
        return false;
    }
}