<?php namespace EventSourcery\EventDispatch;

use EventSourcery\Collections\TypedCollection;

final class Listeners extends TypedCollection {

    protected $collectionType = Listener::class;
}