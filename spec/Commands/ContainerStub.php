<?php namespace spec\EventSourcery\Commands;

use Psr\Container\ContainerInterface;

class ContainerStub implements ContainerInterface {

    private $objects = [];

    public function set($id, $object) {
        $this->objects[$id] = $object;
    }

    public function get($id) {
        if ( ! isset($this->objects[$id])) {
            throw new \Exception('No resolution target set for ' . $id);
        }
        return $this->objects[$id];
    }

    public function has($id) {
        return isset($this->objects[$id]);
    }
}