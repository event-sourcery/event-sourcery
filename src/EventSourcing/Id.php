<?php namespace EventSourcery\EventSourcing;

use Ramsey\Uuid\Uuid;

abstract class Id {

    /** @var string */
    protected $id;

    protected function __construct(string $id) {
        $this->id = $id;
    }

    public static function fromString(string $id): Id {
        return new static($id);
    }

    public static function generate(): Id {
        return new static(Uuid::uuid4()->toString());
    }

    public function toString(): string {
        return $this->id;
    }

    public function __toString(): string {
        return $this->toString();
    }

    public function equals(self $that): bool {
        if (get_class($this) !== get_class($that)) {
            throw new CannotCompareDifferentIds;
        }
        return $this->id === $that->id;
    }
}
