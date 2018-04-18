<?php namespace spec\EventSourcery\EventSourcing;

use PhpSpec\ObjectBehavior;

class ImmutableTimestampSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedThrough('fromString', ['2017-01-02 03:04:05', 'Europe/Amsterdam']);
    }

    function it_creates_mysql_date_time_strings() {
        $this->toMysqlDateTime()->shouldBe('2017-01-02 03:04:05');
    }
}
