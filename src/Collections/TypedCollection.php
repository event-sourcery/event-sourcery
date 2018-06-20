<?php namespace EventSourcery\EventSourcery\Collections;

/**
 * TypedCollection is an abstract collection class that can be
 * used to create collections that hold items only of a defined
 * type.
 *
 * See: DomainEvents, StreamEvents
 */
abstract class TypedCollection extends Collection {

    /** @var string $collectionType */
    protected $collectionType;

    protected function __construct(array $items = []) {
        $this->guardType($items);
        parent::__construct($items);
    }

    /**
     * ensure that items added to the collection conform to
     * the defined collection type
     *
     * @param $items
     */
    protected function guardType($items) : void {
        // this allows guardType($items) to receive
        // both a single item or an array of items
        if ( ! is_array($items)) {
            $items = array($items);
        }

        // throw an exception if any items are not
        // an instance of the required type
        foreach ($items as $item) {
            if ( ! $item instanceof $this->collectionType) {
                throw new \InvalidArgumentException(
                    "Got " . (is_object($item) ? get_class($item) : $item) . " but expected " . $this->collectionType
                );
            }
        }
    }

    /**
     * add an item to the collection and ensure that it is
     * of the appropriate type
     *
     * @param $item
     * @return Collection
     */
    public function add($item) : Collection {
        $this->guardType($item);
        return parent::add($item);
    }

    /**
     * return a new instance of this collection with values that
     * have been transformed by the provided function.
     *
     * if the values conform to the defined type for this collection
     * then the new collection instance will be of the same type
     * as the original.
     *
     * if the values do not conform to the defined type then a
     * generic untyped collection will be returned instead.
     *
     * @param callable $f
     * @return Collection
     */
    public function map(Callable $f) : Collection {
        try {
            return new static(array_map($f, $this->items));
        } catch (\Exception $e) {
            return new Collection(array_map($f, $this->items));
        }
    }
}
