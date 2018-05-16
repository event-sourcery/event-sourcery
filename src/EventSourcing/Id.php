<?php namespace EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\Serialization\SerializableValue;
use Ramsey\Uuid\Uuid;

abstract class Id implements SerializableValue {

    /** @var string */
    protected $id;

    protected function __construct(string $id) {
        $this->id = $id;
    }

    public static function generate(): Id {
        return new static(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): Id {
        return new static($id);
    }

    public function toString(): string {
        return $this->id;
    }

    public function equals(self $that): bool {
        if (get_class($this) !== get_class($that)) {
            throw new CannotCompareDifferentIds;
        }
        return $this->id === $that->id;
    }

    public function serialize(): string {
        return $this->toString();
    }

    public static function deserialize(string $string) {
        return static::fromString($string);
    }
}
