<?php namespace EventSourcery\Collections;

class Collection implements \IteratorAggregate, \Countable {

    var $items = [];

    protected function __construct(array $items = []) {
        $this->items = $items;
    }

    public static function make(array $items = []) : Collection {
        return new static($items);
    }

    public function count() : int {
        return count($this->items);
    }

    public function add($value) : Collection {
        $newItems = $this->items;
        $newItems[] = $value;
        return new static($newItems);
    }

    public function each(Callable $f) : void {
        foreach ($this->items as $i) {
            $f($i);
        }
    }

    public function equals(Collection $that) : bool {
        return
            get_class($this) == get_class($that) &&
            $this->items === $that->items;
    }

    public function map(Callable $f) : Collection {
        return new static(array_map($f, $this->items));
    }

    public function reduce(Callable $f, $initial = null) {
        return array_reduce($this->items, $f, $initial);
    }

    public function filter(Callable $f) {
        return new static(array_filter($this->items, $f));
    }

    public function first() {
        return array_values($this->items)[0];
    }

    public function toArray() : array {
        return $this->items;
    }

    public function copy() : Collection {
        return new static($this->items);
    }

    public function getIterator() : \ArrayIterator {
        return new \ArrayIterator($this->items);
    }
}