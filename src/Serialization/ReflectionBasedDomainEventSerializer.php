<?php namespace EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEvent;
use EventSourcery\EventSourcery\EventSourcing\DomainEventClassMap;
use EventSourcery\EventSourcery\PersonalData\PersonalDataKey;
use EventSourcery\EventSourcery\PersonalData\PersonalKey;
use EventSourcery\EventSourcery\PersonalData\SerializablePersonalDataValue;
use EventSourcery\Laravel\LaravelPersonalDataStore;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

class ReflectionBasedDomainEventSerializer implements DomainEventSerializer {

    /** @var DomainEventClassMap */
    private $eventClasses;
    /** @var ValueSerializer */
    private $valueSerializer;
    /**
     * @var LaravelPersonalDataStore
     */
    private $personalDataStore;

    public function __construct(DomainEventClassMap $eventClasses, ValueSerializer $valueSerializer, LaravelPersonalDataStore $personalDataStore) {
        $this->eventClasses = $eventClasses;
        $this->valueSerializer = $valueSerializer;
        $this->personalDataStore = $personalDataStore;
    }

    // change to use new reflection based serializer
    public function serialize(DomainEvent $event): string {
        $reflect = new ReflectionObject($event);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE);

        return json_encode([
            'eventName' => $this->eventNameForClass(get_class($event)),
            'fields'    => $this->serializeFields($props, $event),
        ]);
    }

    private function serializeFields($props, $event) {
        array_map(function (ReflectionProperty $prop) use (&$fields, $event) {
            /** @var ReflectionProperty $prop */
            $prop->setAccessible(true);
            $fields[$prop->getName()] = $this->valueSerializer->serialize($prop->getValue($event));
        }, $props);

        return $fields;
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
                $param->getName(),
            ];
        }

        // get the values for the constructor fields from the serialized event
        $constParamValues = [];
        foreach ($constParams as $constParam) {
            list($type, $name) = $constParam;
            $fields = (array) $serialized['fields'];
            if ( ! isset($fields[$name])) {
                throw new \Exception("Cannot find serialized field {$name}.");
            }
            $constParamValues[] = [
                $type,
                $name,
                $fields[$name],
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
                    if ($this->isPersonalData($type)) {
                        $values = json_decode($value, true);
                        $po = $this->personalDataStore->retrieveData(
                            PersonalKey::fromString($values['personalKey']),
                            PersonalDataKey::fromString($values['dataKey'])
                        );
                        $finishedConstructorValues[] = $type::deserialize($po->toString());
                    } else {
                        $finishedConstructorValues[] = $type::deserialize($value);
                    }
            };
        }

        // construct
        return new $className(...$finishedConstructorValues);
    }

    public
    function classNameForEvent(string $eventName): string {
        return $this->eventClasses->classNameForEvent($eventName);
    }

    public
    function eventNameForClass(string $className): string {
        return $this->eventClasses->eventNameForClass($className);
    }

    private
    function isPersonalData($type) {

        $reflect = new ReflectionClass($type);

        return $reflect->implementsInterface(SerializablePersonalDataValue::class);
    }
}
