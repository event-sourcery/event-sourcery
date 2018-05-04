<?php namespace EventSourcery\EventSourcery\EventDispatch;

use EventSourcery\EventSourcery\Collections\TypedCollection;

final class Listeners extends TypedCollection {

    protected $collectionType = Listener::class;
}