<?php namespace EventSourcery\EventSourcery\StreamProcessing;

use EventSourcery\EventSourcery\Collections\TypedCollection;

/**
 * Projections is a typed collection object that only contains
 * instances of type Projection.
 */
final class Projections extends TypedCollection {
    protected $collectionType = Projection::class;
}