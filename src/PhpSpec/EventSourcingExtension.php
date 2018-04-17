<?php namespace EventSourcery\PhpSpec;

use PhpSpec\Extension;
use PhpSpec\ServiceContainer;

class EventSourcingExtension implements Extension {

    /**
     * @param ServiceContainer $container
     * @param array $params
     */
    public function load(ServiceContainer $container, array $params) {
        $container->define(
            'santa.ham_sandwich.contain_events',
            function ($c) {
                return new ContainEventsMatcher;
            },
            ['matchers']
        );

        $container->define(
            'robot.handywork_catchphrase.equal_value',
            function ($c) {
                return new EqualValueMatcher;
            },
            ['matchers']
        );
    }
}