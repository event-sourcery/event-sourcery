<?php namespace EventSourcery\EventSourcery\Collections;

/**
 * Collection is the standard generic collection class used internally.
 *
 * It is reasonable to use this collection within client applications.
 */
class Collection implements \IteratorAggregate, \Countable {

    var $items = [];

    protected function __construct(array $items = []) {
        $this->items = $items;
    }

    /**
     * construct a collection from an array
     *
     * @param array $items
     * @return Collection
     */
    public static function make(array $items = []) : Collection {
        return new static($items);
    }

    /**
     * retrieve a count of items in the collection
     *
     * @return int
     */
    public function count() : int {
        return count($this->items);
    }

    /**
     * add an item to the collection
     *
     * @param $item
     * @return Collection
     */
    public function add($item) : Collection {
        $newItems = $this->items;
        $newItems[] = $item;
        return new static($newItems);
    }

    /**
     * run a function against each item in the collection
     *
     * @param callable $f
     */
    public function each(Callable $f) : void {
        foreach ($this->items as $i) {
            $f($i);
        }
    }

    /**
     * compare one collection against another. collections are
     * considered equal if both collection classes are of the same
     * type and both item arrays are considered equal with strict
     * comparison.
     *
     * @param Collection $that
     * @return bool
     */
    public function equals(Collection $that) : bool {
        return
            get_class($this) == get_class($that) &&
            $this->items === $that->items;
    }

    /**
     * return a new collection containing items transformed from the
     * existing collection using the transformation function provided.
     *
     * @param callable $f
     * @return Collection
     */
    public function map(Callable $f) : Collection {
        return new static(array_map($f, $this->items));
    }

    /**
     * return a single value reduced from the collection by the
     * provided function.
     *
     * @param callable $f
     * @param null $initial
     * @return mixed
     */
    public function reduce(Callable $f, $initial = null) {
        return array_reduce($this->items, $f, $initial);
    }

    /**
     * return a new collection instance equaling the original
     * collection but filtered by the provided function.
     *
     * @param callable $f
     * @return Collection
     */
    public function filter(Callable $f) {
        return new static(array_filter($this->items, $f));
    }

    /**
     * return the first item in the collection. (the head)
     *
     * @return mixed
     */
    public function first() {
        return array_values($this->items)[0];
    }

    /**
     * return an array containing all of the items in the
     * collection.
     *
     * @return array
     */
    public function toArray() : array {
        return $this->items;
    }

    /**
     * create a new collection that is a copy of the original
     * collection.
     *
     * @return Collection
     */
    public function copy() : Collection {
        return new static($this->items);
    }

    /**
     * return an iterator for the collection
     *
     * @return \ArrayIterator
     */
    public function getIterator() : \ArrayIterator {
        return new \ArrayIterator($this->items);
    }
}