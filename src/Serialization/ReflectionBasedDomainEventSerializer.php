<?php namespace EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcery\EventSourcing\DomainEventClassMap;
use EventSourcery\EventSourcery\PersonalData\PersonalDataKey;
use EventSourcery\EventSourcery\PersonalData\PersonalDataStore;
use EventSourcery\EventSourcery\PersonalData\PersonalKey;
use EventSourcery\EventSourcery\PersonalData\SerializablePersonalDataValue;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

/**
 * The ReflectionBasedDomainEventSerializer is a default (yet optional)
 * implementation of DomainEventSerializer which uses reflection to
 * automatically serialize value objects and thus the entire DomainEvent.
 *
 * All value objects must implement either SerializableValue or
 * SerializablePersonalDataValue.
 */
class ReflectionBasedDomainEventSerializer implements DomainEventSerializer {

    /** @var DomainEventClassMap */
    private $eventClasses;
    /** @var ValueSerializer */
    private $valueSerializer;
    /** @var PersonalDataStore */
    private $personalDataStore;

    public function __construct(DomainEventClassMap $eventClasses, ValueSerializer $valueSerializer, PersonalDataStore $personalDataStore) {
        $this->eventClasses = $eventClasses;
        $this->valueSerializer = $valueSerializer;
        $this->personalDataStore = $personalDataStore;
    }

    /**
     * serialize a domain event into event sourcery library
     * storage conventions
     * @param DomainEvent $event
     * @return string
     */
    public function serialize(DomainEvent $event): string {
        $reflect = new ReflectionObject($event);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE);

        return json_encode([
            'eventName' => $this->eventNameForClass(get_class($event)),
            'fields'    => $this->serializeFields($props, $event),
        ]);
    }

    /**
     * Serialize the fields of a domain event into a key => value hash
     *
     * @param $props
     * @param $event
     * @return mixed
     */
    private function serializeFields($props, $event) {
        array_map(function (ReflectionProperty $prop) use (&$fields, $event) {
            /** @var ReflectionProperty $prop */
            $prop->setAccessible(true);
            $fields[$prop->getName()] = $this->valueSerializer->serialize($prop->getValue($event));
        }, $props);

        return $fields;
    }

    /**
     * deserialize a domain event from event sourcery library
     * storage conventions
     *
     * @param array $serialized
     * @return DomainEvent
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function deserialize(array $serialized): DomainEvent {
        $className = $this->classNameForEvent($serialized['eventName']);

        $reflect = new ReflectionClass($className);
        $const = $reflect->getConstructor();

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
            $fields = $serialized['fields'];
            if ( ! isset($fields[$name])) {
                throw new \Exception("Cannot find serialized field {$name} for {$className}.");
            }
            $constParamValues[] = [
                $type,
                $name,
                $fields[$name],
            ];
        }

        // reconstruct the serialized values into the correct type
       return new $className(...array_map(function ($constParamValue) {
            list($type, $name, $value) = $constParamValue;
            switch ($type) {
                case 'string':
                    return (string) $value;
                    break;
                case 'int':
                    return (int) $value;
                    break;
                case 'bool':
                    return (bool) $value;
                    break;
                default:
                    try {
                        if ($this->isPersonalData($type)) {
                            $personalData = $this->personalDataStore->retrieveData(
                                PersonalKey::deserialize($value['personalKey']),
                                PersonalDataKey::deserialize($value['dataKey'])
                            );
                            /** @var SerializableValue $type */
                            return $type::deserialize(json_decode($personalData->toString(), true));
                        } else {
                            /** @var SerializableValue $type */
                            return $type::deserialize($value);
                        }
                    } catch (\TypeError $e) {
                        throw new CouldNotDeserializeValueObject($e);
                    }
            };
        }, $constParamValues));
    }

    /**
     * return the fully qualified class name for the domain event
     * mapped to the string name provided
     *
     * @param string $eventName
     * @return string
     */
    public function classNameForEvent(string $eventName): string {
        return $this->eventClasses->classNameForEvent($eventName);
    }

    /**
     * return the string representation of an event name based on
     * the provided fully qualified class name
     *
     * @param string $className
     * @return string
     */
    public function eventNameForClass(string $className): string {
        return $this->eventClasses->eventNameForClass($className);
    }

    /**
     * returns true if the provided class name implements the
     * SerializablePersonalDataValue interface
     *
     * @param $type
     * @return bool
     * @throws \ReflectionException
     */
    private function isPersonalData($type) {
        $reflect = new ReflectionClass($type);
        return $reflect->implementsInterface(SerializablePersonalDataValue::class);
    }
}
