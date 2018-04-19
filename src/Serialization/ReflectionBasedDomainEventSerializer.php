<?php namespace EventSourcery\Serialization;

use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

class ReflectionBasedDomainEventSerializer implements DomainEventSerializer {

    /** @var DomainEventClassMap */
    private $eventClasses;

    public function __construct(DomainEventClassMap $eventClasses) {
        $this->eventClasses = $eventClasses;
    }

    // change to use new reflection based serializer
    public function serialize(DomainEvent $event): array {
        $reflect = new ReflectionObject($event);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $serialized = [
            'eventName' => $this->eventNameForClass(get_class($event)),
            'fields' => []
        ];

        foreach ($props as $prop) {
            $prop->setAccessible(true);


            // check to see if it's a value object
            if (is_object($prop->getValue($event))) {
                if ($prop->getValue($event) instanceof SerializableValue) {
                    $serialized['fields'][$prop->getName()] = $prop->getValue($event)->toString();
                } else {
                    throw new \Exception("I don't know how to serialize an object of type " . get_class($prop->getValue($event)));
                }
            } else {
                $serialized['fields'][$prop->getName()] = $prop->getValue($event);
            }
        }

        return $serialized;
    }

    public function deserialize(array $serialized): DomainEvent {
        $className = $this->classNameForEvent($serialized['eventName']);

        $reflect = new ReflectionClass($className);
        $const = $reflect->getConstructor();

        // reflect on constructor to get name / type
        $constParams = [];
        foreach ($const->getParameters() as $param) {
            $constParams[] = [
                $param->getType()->getName(),
                $param->getName()
            ];
        }

        // get the values for the constructor fields from the serialized event
        $constParamValues = [];

        foreach ($constParams as $constParam) {
            list($type, $name) = $constParam;

            if ( ! isset($serialized['fields'][$name])) {
                throw new \Exception("Cannot find serialized field {$name}.");
            }

            $constParamValues[] = [
                $type, $name, $serialized['fields'][$name]
            ];
        }

        // reconstruct the serialized values into the correct type
        $finishedConstructorValues = [];

        foreach ($constParamValues as $constParamValue) {
            list($type, $name, $value) = $constParamValue;

            switch ($type) {
                case 'string':
                case 'int':
                case 'bool':
                    $finishedConstructorValues[] = $value;
                    break;
                default:
                    $finishedConstructorValues[] = $type::fromString($value);
//                    throw new \Exception("Could not deserialize type {$type}");
            };
        }

        // construct
        return new $className(...$finishedConstructorValues);
    }

    private function classNameForEvent($eventName): string {
        return $this->eventClasses->classNameForEvent($eventName);
    }

    private function eventNameForClass(string $className): string {
        return $this->eventClasses->eventNameForClass($className);
    }
}
