<?php namespace spec\EventSourcery\EventSourcery\EventSourcing;

use EventSourcery\EventSourcery\EventSourcing\Timestamp;
use PhpSpec\ObjectBehavior;
use function EventSourcery\EventSourcery\PhpSpec\expect;

class TimestampSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedThrough('fromString', ['2017-01-02 03:04:05', 'Europe/Amsterdam']);
    }

    function it_creates_mysql_date_time_strings()
    {
        $this->toMysqlDateTime()->shouldBe('2017-01-02 03:04:05');
    }

    function it_can_be_serialized_and_deserialized()
    {
        $serialized = $this->serialize();
        $deserialized = Timestamp::deserialize($serialized->getWrappedObject());

        expect($deserialized->toMysqlDateTime())->toBe('2017-01-02 03:04:05');
    }

    function it_can_compare_two_timestamps_for_equality()
    {
        $this->beConstructedThrough('fromString', ['2017-01-02 03:04:05', 'Europe/Amsterdam']);

        $two = Timestamp::fromString('2017-01-02 03:04:05', 'Europe/Amsterdam');
        $three = Timestamp::now();

        $this->equals($two)->shouldBe(true);
        $this->equals($three)->shouldBe(false);
    }
}
