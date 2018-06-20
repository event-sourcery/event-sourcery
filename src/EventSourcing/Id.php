<?php namespace EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\Serialization\SerializableValue;
use Ramsey\Uuid\Uuid;

/**
 * The abstract class Id is used to implement any amount
 * of identification objects. An Id object represents the
 * abstract concept of being able to differentiate a specific
 * instance from others.
 */
abstract class Id implements SerializableValue {

    /** @var string */
    protected $id;

    protected function __construct(string $id) {
        $this->id = $id;
    }

    /**
     * generate a new Id instance.
     *
     * the Id is implemented internally with a UUID
     *
     * @return Id
     */
    public static function generate(): Id {
        return new static(Uuid::uuid4()->toString());
    }

    /**
     * generate an Id instance from a string representation
     *
     * @param string $id
     * @return Id
     */
    public static function fromString(string $id): Id {
        return new static($id);
    }

    /**
     * retrieve the string representation of this Id
     *
     * @return string
     */
    public function toString(): string {
        return $this->id;
    }

    /**
     * compare two Id instances for equality
     *
     * Ids are considered equal if they are of the same
     * type and contain the same underlying string
     * representations
     *
     * @param self $that
     * @return bool
     * @throws CannotCompareDifferentIds
     */
    public function equals(self $that): bool {
        if (get_class($this) !== get_class($that)) {
            throw new CannotCompareDifferentIds;
        }
        return $this->id === $that->id;
    }

    /**
     * serialize() returns a string form of the value for persistence
     * which will be deserialized when needed
     *
     * @return string
     */
    public function serialize(): string {
        return $this->toString();
    }

    /**
     * deserialize() returns a value object from a string received
     * from persistence
     *
     * @param string $string
     * @return mixed
     */
    public static function deserialize(string $string) {
        return static::fromString($string);
    }
}
