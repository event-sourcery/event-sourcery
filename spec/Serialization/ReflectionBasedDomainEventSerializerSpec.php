<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\EventSourcing\DomainEventClassMap;
use EventSourcery\EventSourcery\Serialization\CouldNotDeserializeValueObject;
use EventSourcery\EventSourcery\Serialization\ValueSerializer;
use PhpSpec\ObjectBehavior;
use spec\EventSourcery\EventSourcery\PersonalData\PersonalDataStoreStub;

class ReflectionBasedDomainEventSerializerSpec extends ObjectBehavior {

    function let() {
        $personalDataStore = new PersonalDataStoreStub;
        $valueSerializer = new ValueSerializer($personalDataStore);
        $classMap = new DomainEventClassMap;
        $this->beConstructedWith($classMap, $valueSerializer, $personalDataStore);

        $classMap->add('StringEventStub', StringEventStub::class);
        $classMap->add('ArrayEventStub', ArrayEventStub::class);
        $classMap->add('IntEventStub', IntEventStub::class);
        $classMap->add('BoolEventStub', BoolEventStub::class);
        $classMap->add('ValueObjectEventStub', ValueObjectEventStub::class);
        $classMap->add('NestedValueObjectsEventStub', NestedValueObjectsEventStub::class);
        $classMap->add('InvalidNestedValueObjectsEventStub', InvalidNestedValueObjectsEventStub::class);
    }

    function it_can_serialize_strings() {
        $this->serialize(
            new StringEventStub("hats")
        )->shouldReturn('{"eventName":"StringEventStub","fields":{"str":"hats"}}');
    }

    function it_can_deserialize_strings() {
        $obj = $this->deserialize([
            'eventName' => 'StringEventStub',
            'fields' => ['str' => 'hats']
        ]);

        $obj->str->shouldBe("hats");
    }

    function it_can_serialize_arrays_of_strings() {
        $this->serialize(
            new ArrayEventStub(['cats', 'dogs'])
        )->shouldReturn('{"eventName":"ArrayEventStub","fields":{"array":["cats","dogs"]}}');
    }

    function it_can_deserialize_arrays() {
        $obj = $this->deserialize([
            'eventName' => 'ArrayEventStub',
            'fields' => ['array' => ['cats','dogs']]
        ]);

        $obj->array->shouldBe(['cats', 'dogs']);
    }

    function it_can_serialize_ints() {
        $this->serialize(
            new IntEventStub(123)
        )->shouldReturn('{"eventName":"IntEventStub","fields":{"int":123}}');
    }

    function it_can_deserialize_ints() {
        $obj = $this->deserialize([
            'eventName' => 'IntEventStub',
            'fields' => ['int' => 123]
        ]);

        $obj->int->shouldBe(123);
    }

    function it_can_serialize_bools() {
        $this->serialize(
            new BoolEventStub(true)
        )->shouldReturn('{"eventName":"BoolEventStub","fields":{"bool":true}}');

        $this->serialize(
            new BoolEventStub(false)
        )->shouldReturn('{"eventName":"BoolEventStub","fields":{"bool":false}}');
    }

    function it_can_deserialize_bools() {
        $obj = $this->deserialize([
            'eventName' => 'BoolEventStub',
            'fields' => ['bool' => true]
        ]);

        $obj->bool->shouldBe(true);

        $obj = $this->deserialize([
            'eventName' => 'BoolEventStub',
            'fields' => ['bool' => false]
        ]);

        $obj->bool->shouldBe(false);
    }

    function it_can_serialize_value_objects() {
        $this->serialize(
            new ValueObjectEventStub(new ValueObjectStub("str1", 123, "str2", 321))
        )->shouldReturn('{"eventName":"ValueObjectEventStub","fields":{"vo":{"string1":"str1","integer1":123,"string2":"str2","integer2":321}}}');
    }

    function it_can_deserialize_value_objects() {
        $obj = $this->deserialize([
            'eventName' => 'ValueObjectEventStub',
            'fields'    => ['vo' => ['string1' => 'str1', 'integer1' => 123, 'string2' => 'str2', 'integer2' => 321]]
        ]);

        $obj->vo->shouldHaveType(ValueObjectStub::class);
        $obj->vo->string1->shouldBe("str1");
        $obj->vo->integer1->shouldBe(123);
        $obj->vo->string2->shouldBe("str2");
        $obj->vo->integer2->shouldBe(321);
    }

    function it_can_serialize_nested_value_objects() {
        $nested = new NestedValueObjectsStub(new SimpleValueObjectStub('str1'), 'str2');
        $this->serialize(new NestedValueObjectsEventStub($nested))
            ->shouldReturn('{"eventName":"NestedValueObjectsEventStub","fields":{"vo":{"simple":{"string1":"str1"},"string1":"str2"}}}');
    }

    function it_can_deserialize_nested_value_objects() {
        $obj = $this->deserialize([
            'eventName' => 'NestedValueObjectsEventStub',
            'fields' => ['vo' => ['simple' => ['string1' => 'str1'], 'string1' => 'str2']]
        ]);
        $obj->vo->shouldHaveType(NestedValueObjectsStub::class);
    }

    function it_throw_when_deserializing_nested_value_objects_without_calling_deserialize_on_the_nested_objects() {
        $this->shouldThrow(CouldNotDeserializeValueObject::class)->during('deserialize', [
            [
                'eventName' => 'InvalidNestedValueObjectsEventStub',
                'fields' => ['vo' => ['simple' => ['string1' => 'str1'], 'string1' => 'str2']]
            ]
        ]);
    }
}
