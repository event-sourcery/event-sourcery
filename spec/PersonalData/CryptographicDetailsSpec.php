<?php namespace spec\EventSourcery\EventSourcery\PersonalData;

use EventSourcery\EventSourcery\PersonalData\CryptographicDetails;
use function EventSourcery\EventSourcery\PhpSpec\expect;
use PhpSpec\ObjectBehavior;

class CryptographicDetailsSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('type', []);
    }

    function it_is_initializable() {
        $this->shouldHaveType(CryptographicDetails::class);
    }

    function it_can_be_serialized() {
        $details = new CryptographicDetails('encryptionType', ['key1' => 'val']);
        $serialized = $details->serialize();
        $crypto = CryptographicDetails::deserialize($serialized);
        expect($crypto)->type()->toBe('encryptionType');
        expect($crypto)->key('key1')->toBe('val');
    }
}
