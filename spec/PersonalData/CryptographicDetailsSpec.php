<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\CryptographicDetails;
use EventSourcery\EventSourcery\PersonalData\CryptographicDetailsDoNotContainKey;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class CryptographicDetailsSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('type', []);
    }

    function it_is_initializable() {
        $this->shouldHaveType(CryptographicDetails::class);
    }

    function it_has_an_encryption_type() {
        $this->encryption()->shouldBe('type');
    }

    function it_stores_and_provides_details() {
        $this->beConstructedWith('type', ['key' => '123', 'hat' => '234']);

        $this->key('key')->shouldBe('123');
        $this->key('hat')->shouldBe('234');
    }

    function it_throws_when_accessing_nonexisting_keys() {
        $this->shouldThrow(CryptographicDetailsDoNotContainKey::class)->during('key', ['thiskeydoesntexist']);
    }

    function it_can_be_serialized() {
        $details = new CryptographicDetails('encryptionType', ['key1' => 'val', 'hats' => 'cats']);

        $serialized = $details->serialize();

        $crypto = CryptographicDetails::deserialize($serialized);

        expect($crypto)->encryption()->toBe('encryptionType');
        expect($crypto)->key('key1')->toBe('val');
        expect($crypto)->key('hats')->toBe('cats');
    }
}
