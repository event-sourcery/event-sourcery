<?php namespace spec\EventSourcery\EventSourcing;

use EventSourcery\EventSourcing\DomainEventClassMap;
use PhpSpec\ObjectBehavior;
use spec\EventSourcery\Serialization\BoolEventStub;
use spec\EventSourcery\Serialization\IntEventStub;
use spec\EventSourcery\Serialization\StringEventStub;
use spec\EventSourcery\Serialization\ValueObject;
use spec\EventSourcery\Serialization\ValueObjectEventStub;

class ReflectionBasedDomainEventSerializerSpec extends ObjectBehavior {

    function let() {
        $classMap = new DomainEventClassMap();
        $this->beConstructedWith($classMap);

        $classMap->add('StringEventStub', StringEventStub::class);
        $classMap->add('IntEventStub', IntEventStub::class);
        $classMap->add('BoolEventStub', BoolEventStub::class);
        $classMap->add('ValueObjectEventStub', ValueObjectEventStub::class);
    }

    function it_can_serialize_strings() {
        $this->serialize(
            new StringEventStub("hats")
        )->shouldReturn([
            'eventName' => 'StringEventStub',
            'fields' => ['str' => 'hats']
        ]);
    }

    function it_can_deserialize_strings() {
        $obj = $this->deserialize([
            'eventName' => 'StringEventStub',
            'fields' => ['str' => 'hats']
        ]);

        $obj->str->shouldBe("hats");
    }

    function it_can_serialize_ints() {
        $this->serialize(
            new IntEventStub(123)
        )->shouldReturn([
            'eventName' => 'IntEventStub',
            'fields' => ['int' => 123]
        ]);
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
        )->shouldReturn([
            'eventName' => 'BoolEventStub',
            'fields' => ['bool' => true]
        ]);

        $this->serialize(
            new BoolEventStub(false)
        )->shouldReturn([
            'eventName' => 'BoolEventStub',
            'fields' => ['bool' => false]
        ]);
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
            new ValueObjectEventStub(new ValueObject("str1", 123, "str2", 321))
        )->shouldReturn([
            'eventName' => 'ValueObjectEventStub',
            'fields' => ['vo' => '{"string1":"str1","integer1":123,"string2":"str2","integer2":321}']
        ]);
    }

    function it_can_deserialize_value_objects() {
        $obj = $this->deserialize([
            'eventName' => 'ValueObjectEventStub',
            'fields'    => ['vo' => '{"string1":"str1","integer1":123,"string2":"str2","integer2":321}']
        ]);

        $obj->vo->shouldHaveType(ValueObject::class);
        $obj->vo->string1->shouldBe("str1");
        $obj->vo->integer1->shouldBe(123);
        $obj->vo->string2->shouldBe("str2");
        $obj->vo->integer2->shouldBe(321);
    }
}
