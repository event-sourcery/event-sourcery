<?php namespace EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcery\EventSourcing\DomainEventClassMap;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

class ReflectionBasedDomainEventSerializer implements DomainEventSerializer {

    /** @var DomainEventClassMap */
    private $eventClasses;
    /** @var ValueSerializer */
    private $valueSerializer;

    public function __construct(DomainEventClassMap $eventClasses, ValueSerializer $valueSerializer) {
        $this->eventClasses    = $eventClasses;
        $this->valueSerializer = $valueSerializer;
    }

        // change to use new reflection based serializer
    public function serialize(DomainEvent $event): string {
        $reflect = new ReflectionObject($event);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE);

        return json_encode([
            'eventName' => $this->eventNameForClass(get_class($event)),
            'fields'    => $this->serializeFields($props),
        ]);
    }

    private function serializeFields($props) {
        array_map(function (ReflectionProperty $prop) use (&$fields) {
            /** @var ReflectionProperty $prop */
            $prop->setAccessible(true);
            $fields[$prop->getName()] = $this->valueSerializer->serialize($prop->getName());
        }, $props);

        return $fields;
    }

    public function deserialize(array $serialized): DomainEvent {
        $className = $this->classNameForEvent($serialized['eventName']);

        $reflect = new ReflectionClass($className);
        $const   = $reflect->getConstructor();

        // reflect on constructor to get name / type
        $constParams = [];
        foreach ($const->getParameters() as $param) {
            $constParams[] = [
                $param->getType()->getName(),
                $param->getName(),
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
                $type, $name, $serialized['fields'][$name],
            ];
        }

        // reconstruct the serialized values into the correct type
        $finishedConstructorValues = [];

        foreach ($constParamValues as $constParamValue) {
            list($type, $name, $value) = $constParamValue;

            switch ($type) {
                case 'string':
                    $finishedConstructorValues[] = (string) $value;
                    break;
                case 'int':
                    $finishedConstructorValues[] = (int) $value;
                    break;
                case 'bool':
                    $finishedConstructorValues[] = (bool) $value;
                    break;
                default:
                    $finishedConstructorValues[] = $type::fromString($value);
//                    throw new \Exception("Could not deserialize type {$type}");
            };
        }

        // construct
        return new $className(...$finishedConstructorValues);
    }

    public function classNameForEvent(string $eventName): string {
        return $this->eventClasses->classNameForEvent($eventName);
    }

    public function eventNameForClass(string $className): string {
        return $this->eventClasses->eventNameForClass($className);
    }
}
