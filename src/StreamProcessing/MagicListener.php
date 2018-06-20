<?php namespace EventSourcery\EventSourcery\StreamProcessing;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;

/**
 * MagicListener is a default (yet optional) Listener of
 * domain events which provides a developer-friendly ui
 * with which to respond to multiple events.
 */
abstract class MagicListener {

    /**
     * receives domain events and routes them to a method with their name
     *
     * @param DomainEvent $event
     */
    public function handle(DomainEvent $event) : void {
        $method = lcfirst($this->getShortName($event));
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }

    /**
     * takes a fully qualified class name and returns a short class-name
     * only version to match conventions.
     *
     * @param $class
     * @return string
     */
    private function getShortName($class) : string {
        $className = explode('\\', get_class($class));
        return $className[count($className) - 1];
    }
}
