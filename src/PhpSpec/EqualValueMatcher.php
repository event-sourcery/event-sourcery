<?php namespace EventSourcery\PhpSpec;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\Matcher;

class EqualValueMatcher implements Matcher {

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
        return $name == 'equalValue' || $name == 'equalValues';
    }

    /**
     * Evaluates positive match.
     *
     * @param string $name
     * @param mixed $subject
     * @param array $arguments
     */
    public function positiveMatch($name, $subject, array $arguments) {
        $argumentCount = count($arguments);

        if ($argumentCount == 1) {
            $this->compare($subject, $arguments[0]);
        } else {
            for ($i=0; $i<$argumentCount; $i++) {
                $this->compare($subject[0], $arguments[0]);
            }
        }
        return true;
    }

    private function compare($subject, $argument) {
        // compare scalars
        if ((is_null($subject) || is_null($argument)) && $subject !== $argument) {
            if (is_null($subject)) {
                $subject = 'null';
            }
            if (is_null($argument)) {
                $argument = 'null';
            }
            throw new FailureException("Cannot compare '$subject' with '$argument'.");
        }
        if (is_scalar($subject) && is_scalar($argument)) {
            return $subject === $argument;
        }
        // don't compare scalars vs objects
        if (is_scalar($subject) || is_scalar($argument)) {
            $item1 = is_scalar($subject) ? "<label>scalar</label> value <value>{$subject}</value>" : '<label>object</label> of type <value>' . get_class($subject) . '</value>';
            $item2 = is_scalar($argument) ? "<label>scalar</label> value <value>{$argument}</value>" : '<label>object</label> of type <value>' . get_class($argument) . '</value>';
            throw new FailureException("Cannot compare $item1 with $item2.");
        }
        // compare objects
        if (get_class($subject) !== get_class($argument)) {
            throw new FailureException("Values of types <label>" . get_class($subject) . "</label> and <label>" . get_class($argument) . "</label> cannot be compared.");
        }
        if ( ! $subject->equals($argument)) {
            throw new FailureException('Value of type <label>' . get_class($subject) . "</label> <value>{$subject->toString()}</value> should equal <value>{$argument->toString()}</value> but does not.");
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
        if ($subject->equals($arguments[0])) {
            throw new FailureException('<label>' . get_class($subject) . "</label> <value>{$subject->toString()}</value> should not equal <value>{$arguments[0]->toString()}</value> but does.");
        }
    }

    /**
     * Returns matcher priority.
     *
     * @return integer
     */
    public function getPriority(): int {
        return 0;
    }
}