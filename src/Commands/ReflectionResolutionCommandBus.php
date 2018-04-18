<?php namespace EventSourcery\Commands;

use Psr\Container\ContainerInterface as Container;
use ReflectionClass;

class ReflectionResolutionCommandBus implements CommandBus {

    /** @var Container */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function execute(Command $command) {
        $command->execute(...$this->instantiateParameters($command));
    }

    private function instantiateParameters(Command $command) {
        return array_map(function(\ReflectionParameter $param) {
            return $this->container->get($param->getType()->getName());
        }, (new ReflectionClass(get_class($command)))->getMethod('execute')->getParameters());
    }
}