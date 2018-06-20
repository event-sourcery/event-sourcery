<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\Collections\TypedCollection;

/**
 * Listeners is a typed collection object that only contains
 * instances of type Listener.
 */
final class Listeners extends TypedCollection {
    protected $collectionType = Listener::class;
}