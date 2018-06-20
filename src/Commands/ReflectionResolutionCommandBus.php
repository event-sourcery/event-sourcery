<?php namespace EventSourcery\EventSourcery\Commands;

use Psr\Container\ContainerInterface as Container;
use ReflectionClass;

/**
 * ReflectionResolutionCommandBus is a default (yet optional)
 * implementation of a CommandBus that will execute a command's
 * execute() method by reflecting upon its signature, instantiating
 * the required arguments from PSR compatible container, and then
 * injecting them into the execute() method on the Command.
 */
class ReflectionResolutionCommandBus implements CommandBus {

    /** @var Container */
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * execute a command
     *
     * @param Command $command
     * @throws \ReflectionException
     */
    public function execute(Command $command): void {
        $command->execute(...$this->instantiateParameters($command));
    }

    /**
     * instantiate the parameters for the command's execution method
     * using the constructor injected container.
     *
     * @param Command $command
     * @return array
     * @throws \ReflectionException
     */
    private function instantiateParameters(Command $command) {
        return array_map(function(\ReflectionParameter $param) {
            return $this->container->get($param->getType()->getName());
        }, (new ReflectionClass(get_class($command)))->getMethod('execute')->getParameters());
    }
}