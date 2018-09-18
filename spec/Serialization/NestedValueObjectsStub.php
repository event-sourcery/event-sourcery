<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

class NestedValueObjectsStub implements SerializableValue {

    /** @var SimpleValueObjectStub */
    public $simple;
    /** @var string */
    public $string1;

    public function __construct(SimpleValueObjectStub $simple, string $string1) {
        $this->simple = $simple;
        $this->string1 = $string1;
    }

    public function serialize(): array {
        return [
            'simple' => $this->simple->serialize(),
            'string1' => $this->string1,
        ];
    }

    public static function deserialize(array $data) {
        return new static(SimpleValueObjectStub::deserialize($data['simple']), $data['string1']);
    }
}