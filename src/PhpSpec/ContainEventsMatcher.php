<?php namespace EventSourcery\PhpSpec;

use EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcing\DomainEvents;
use EventSourcery\EventSourcing\StreamEvent;
use EventSourcery\EventSourcing\StreamEvents;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\Matcher;

/**
 * This whole class is a huge mess and has sloooowly evolved over time.
 * Once it's rewritten though, it'll be glorious.
 */
class ContainEventsMatcher implements Matcher {

    /**
     * Checks if matcher supports provided subject and matcher name.
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     *
     * @return Boolean
     */
    public function supports(string $name, $subject, array $arguments): bool {
        return $name === 'containEvents' || $name === 'containEvent';
    }

    /**
     * Evaluates positive match.
     *
     * Yes, I know that this is some really hard to understand code.
     * This is one of the more interesting areas to improve upon
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     */
    public function positiveMatch($name, $subject, array $arguments) {
        list($realEvents, $targetEvents) = $this->formatArguments($subject, $arguments);

        $notFoundEvents = $this->eventsNotFound($realEvents, $targetEvents);

        if ( ! empty($notFoundEvents)) {
            $eventNames = join(', ', array_map(function (DomainEvent $event) {
                return '<label>' . get_class($event) . '</label>';
            }, $notFoundEvents));

            throw new FailureException("Expected matching event(s) {$eventNames} not found.");
        }
    }

    /**
     * Evaluates negative match.
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     */
    public function negativeMatch($name, $subject, array $arguments) {
        list($realEvents, $targetEvents) = $this->formatArguments($subject, $arguments);

        $notFoundEvents = $this->eventsNotFound($realEvents, $targetEvents);

        if (empty($notFoundEvents) || count($notFoundEvents) != $targetEvents->count()) {
            $eventNames = join(', ', array_map(function (DomainEvent $event) {
                return '<label>' . get_class($event) . '</label>';
            }, $notFoundEvents));

            throw new FailureException("Found unexpected event(s) {$eventNames}.");
        }
    }

    /**
     * Returns matcher priority.
     *
     * @return integer
     */
    public function getPriority(): int {
        return 50;
    }

    /**
     * @param $subject
     * @param array $arguments
     * @return array
     */
    private function formatArguments($subject, array $arguments):array {
        $realEvents = $subject;

        if ($realEvents instanceof StreamEvents) {
            $realEvents = DomainEvents::make(
                $realEvents->map(function(StreamEvent $streamEvent) {
                    return $streamEvent->event();
                })->toArray()
            );
        } elseif ($realEvents instanceof DomainEvent) {
            $realEvents = DomainEvents::make([$realEvents]);
        }

        $targetEvents = DomainEvents::make(
            is_array($arguments[0]) ? $arguments[0] : [$arguments[0]]
        );

        return array($realEvents, $targetEvents);
    }

    private function eventsNotFound($realEvents, $targetEvents) {
        return $targetEvents->filter(function ($targetEvent) use ($realEvents) {
            return ! $this->eventIsFound($targetEvent, $realEvents);
        })->toArray();
    }

    // returns true if an event is found

    private function eventIsFound(DomainEvent $targetEvent, DomainEvents $realEvents) {
        $found = $realEvents->filter(function ($realEvent) use ($targetEvent) {
            try {
                $eventsAreEqual = $this->eventsAreEqual($realEvent, $targetEvent);
            } catch (FailureException $e) {
                $eventsAreEqual = false;
            }
            return $eventsAreEqual;
        });

        return $found->count() != 0;
    }

    // pull requests accepted

    private function eventsAreEqual($e1, $e2) {
        // events aren't equal if they have different classes
        if (get_class($e1) != get_class($e2)) {
            return false;
        }

        // compare their values..
        $reflection = new \ReflectionClass($e1);

        $fields = array_map(function ($property) {
            return $property->name;
        }, $reflection->getProperties());

        $allMatch = true;

        foreach ($fields as $field) {
            // compare the single field across both events
            $property = new \ReflectionProperty(get_class($e1), $field);
            $property->setAccessible(true);
            $e1Value = $property->getValue($e1);
            $e2Value = $property->getValue($e2);

            if (is_array($e1Value)) {
                // this part isn't done, jsut copied, think it through
                for ($i=0; $i<count($e1Value); $i++) {
                    $setMatches = $this->compareProperties($e1, $e1Value[$i], $e2Value[$i], $property);
                    if ( ! $setMatches) {
                        $allMatch = false;
                    }
                }
            } else {
                $setMatches = $this->compareProperties($e1, $e1Value, $e2Value, $property);
                if ( ! $setMatches) {
                    $allMatch = false;
                }
            }
        }

        return $allMatch;
    }

    /**
     * @param $e1
     * @param $e1Value
     * @param $e2Value
     * @param $property
     * @return bool
     * @throws FailureException
     */
    private function compareProperties($e1, $e1Value, $e2Value, $property) {
        $setMatches = true;

        if (is_scalar($e1Value)) {
            if ($e1Value !== $e2Value) {
                throw new FailureException("event <label>" . get_class($e1) . "</label> field <code>" . $property->getName() . "</code> expected <value>{$e2Value}</value> but got <value>{$e1Value}</value>.");
                $setMatches = false;
            }
        } elseif ($e1Value instanceof \DateTimeImmutable) {
            if ($e1Value->format('Y-m-d H:i:s') !== $e2Value->format('Y-m-d H:i:s')) {
                throw new FailureException("event <label>" . get_class($e1) . "</label> field <code>" . $property->getName() . "</code> expected <value>{$e2Value->format('Y-m-d H:i:s')}</value> but got <value>{$e1Value->format('Y-m-d H:i:s')}</value>.");
                $setMatches = false;
            }
        } else {
            if ( ! $e1Value->equals($e2Value)) {
                throw new FailureException("event <label>" . get_class($e1) . "</label> field <code>" . $property->getName() . "</code> expected <value>{$e2Value->toString()}</value> but got <value>{$e1Value->toString()}</value>.");
                $setMatches = false;
            }
        }
        return $setMatches;
    }
}

