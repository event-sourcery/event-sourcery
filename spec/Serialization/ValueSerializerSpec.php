<?php namespace spec\EventSourcery\EventSourcery\Serialization;

use EventSourcery\EventSourcery\PersonalData\PersonalDataStore;
use EventSourcery\EventSourcery\PersonalData\PersonalKey;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use EventSourcery\EventSourcery\Serialization\ValueSerializer;
use PhpSpec\ObjectBehavior;
use spec\EventSourcery\EventSourcery\PersonalData\PersonalDataStoreStub;

class ValueSerializerSpec extends ObjectBehavior {

    /** @var PersonalDataStore */
    private $dataStore;

    function let() {
        $this->dataStore = new PersonalDataStoreStub();
        $this->beConstructedWith($this->dataStore);
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

        // serialize object - a bit hokey how this all works probably
        $serialized = $this->serialize(new PersonalDataValueObjectStub($key, 'a', 1, 'b', 2))->getWrappedObject();
        $deserialized = $this->deserializePersonalValue(PersonalDataValueObjectStub::class, $serialized);

        // check values
        $deserialized->personalKey()->serialize()->shouldEqual($key->serialize());
        $deserialized->string1()->shouldBe('a');
        $deserialized->integer1()->shouldBe(1);
        $deserialized->string2()->shouldBe('b');
        $deserialized->integer2()->shouldBe(2);
    }
}