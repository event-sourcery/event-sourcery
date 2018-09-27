<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\Serialization\SerializableValue;

class SimpleValueObjectStub implements SerializableValue {

    public function string1(): string {
        return $this->string1;
    }

    /** @var string */
    public $string1;

    public function __construct(string $string1) {
        $this->string1 = $string1;
    }

    public function serialize(): array{
        return [
            'string1' => $this->string1,
        ];
    }

    public static function deserialize(array $data) {
        return new static($data['string1']);
    }
}