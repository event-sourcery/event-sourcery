<?php namespace spec\EventSourcery\EventSourcery\EventSourcing;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use EventSourcery\EventSourcery\EventSourcing\Timestamp;
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

    function it_can_be_used_as_a_string()
    {
        $this->__toString()->shouldBe('2017-01-02 03:04:05');
    }

    function it_creates_a_datetime_object()
    {
        $this->toDateTime()->shouldHaveType(DateTimeImmutable::class);
    }

    function it_can_return_formatted_strings()
    {
        $this->format('Y-m-d H:i:s')->shouldBe('2017-01-02 03:04:05');
        $this->format('H:i:s')->shouldBe('03:04:05');
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
