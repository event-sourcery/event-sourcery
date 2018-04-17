<?php namespace EventSourcery\CommandDispatch;

use Psr\Container\ContainerInterface as Container;
use ReflectionClass;

class ReflectionResolutionCommandBus implements CommandBus {

    /** @var Container */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function execute(Command $c) {
        $c->execute(...$this->instantiateParameters($c));
    }

    private function instantiateParameters($c) {
        return array_map(function(\ReflectionParameter $param) {
            return $this->container->get($param->getType()->getName());
        }, (new ReflectionClass(get_class($c)))->getMethod('execute')->getParameters());
    }
}