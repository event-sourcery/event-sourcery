<?php namespace spec\EventSourcery\Serialization;

use EventSourcery\PersonalData\PersonalKey;
use function EventSourcery\PhpSpec\expect;
use EventSourcery\Serialization\ValueSerializer;
use PhpSpec\ObjectBehavior;
use spec\EventSourcery\PersonalData\PersonalDataStoreStub;

class ValueSerializerSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith(new PersonalDataStoreStub());
    }

    function it_is_initializable() {
        $this->shouldHaveType(ValueSerializer::class);
    }

    function it_can_serialize_strings() {
        $this->serialize('hats')->shouldBe('hats');
        $this->serialize('cats')->shouldBe('cats');
    }

    function it_can_serialize_ints() {
        $this->serialize(1)->shouldBe(1);
        $this->serialize(2)->shouldBe(2);
    }

    function it_can_serialize_bools() {
        $this->serialize(true)->shouldBe(true);
        $this->serialize(false)->shouldBe(false);
    }

    function it_can_serialize_serializable_value_objects() {
        $serialized = $this->serialize(new ValueObjectStub('a', 1, 'b', 2))->getWrappedObject();
        $deserialized = expect(ValueObjectStub::deserialize($serialized));

        $deserialized->string1()->shouldBe('a');
        $deserialized->integer1()->shouldBe(1);
        $deserialized->string2()->shouldBe('b');
        $deserialized->integer2()->shouldBe(2);
    }

    function it_can_serialize_serializable_personal_data_value_objects() {
        // add crypto for key 'ham'
        $key = PersonalKey::fromString("ham");

        // serialize object
        $serialized = $this->serialize(new PersonalDataValueObjectStub($key, 'a', 1, 'b', 2))->getWrappedObject();

        $deserialized = $this->deserializePersonalValue(PersonalDataValueObjectStub::class, $serialized);

        $deserialized->personalKey()->serialize()->shouldEqual($key->serialize());
        $deserialized->string1()->shouldBe('a');
        $deserialized->integer1()->shouldBe(1);
        $deserialized->string2()->shouldBe('b');
        $deserialized->integer2()->shouldBe(2);
    }
}