<?php namespace EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\PersonalData\PersonalDataStore;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

class ReflectionBasedDomainEventSerializer implements DomainEventSerializer {

    /** @var DomainEventClassMap */
    private $eventClasses;
    /** @var PersonalDataStore */
    private $personalData;
    /** @var ValueSerializer */
    private $valueSerializer;

    public function __construct(DomainEventClassMap $eventClasses, ValueSerializer $valueSerializer) {
        $this->eventClasses    = $eventClasses;
        $this->personalData    = $personalData;
        $this->valueSerializer = $valueSerializer;
    }

    // change to use new reflection based serializer
    public function serialize(DomainEvent $event): array {
        $reflect = new ReflectionObject($event);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE);

        return [
            'eventName' => $this->eventNameForClass(get_class($event)),
            'fields'    => array_map(function (ReflectionProperty $prop) {
                /** @var ReflectionProperty $prop */
                $prop->setAccessible(true);
                $serialized['fields'][$prop->getName()] = $this->valueSerializer->serialize($prop->getValue());
            }, $props),
        ];
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
