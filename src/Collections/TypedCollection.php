<?php namespace EventSourcery\Collections;

abstract class TypedCollection extends Collection {

    /** @var string $collectionType */
    protected $collectionType;

    protected function __construct(array $items = []) {
        $this->guardType($items);
        parent::__construct($items);
    }

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

    public function add($value) : Collection {
        $this->guardType($value);
        return parent::add($value);
    }

    public function map(Callable $f) : Collection {
        try {
            return new static(array_map($f, $this->items));
        } catch (\Exception $e) {
            return new Collection(array_map($f, $this->items));
        }
    }
}
