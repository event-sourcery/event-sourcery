<?php namespace spec\EventSourcery\EventSourcery\StreamProcessing;

use EventSourcery\EventSourcery\StreamProcessing\Projections;
use PhpSpec\ObjectBehavior;
use function EventSourcery\EventSourcery\PhpSpec\expect;

class ProjectionManagerSpec extends ObjectBehavior {

    private $projection;

    function let() {
        $this->projection = new ProjectionStub;
        $this->beConstructedWith(Projections::make([$this->projection]));
    }

    function it_can_return_a_collection_of_projections() {
        $this->all()->shouldHaveType(Projections::class);
        $this->all()->shouldHaveCount(1);
    }

    function it_can_track_additional_projections() {
        $this->add(new ProjectionStub());
        $this->all()->shouldHaveCount(2);
    }

    function it_can_list_projections() {
        $this->listNames()->shouldContain($this->projection->name());
    }

    function it_can_reset_all_projections() {
        $this->resetAll();
        expect($this->projection->resetCount())->toBe(1);
    }

    function it_can_dispatch_events_to_projections() {
        $this->handle(new DomainEventStub());
        expect($this->projection->handleCount())->toBe(1);
        $this->handle(new DomainEventStub());
        expect($this->projection->handleCount())->toBe(2);
    }

    function it_can_return_a_projection_by_name() {
        $this->add(new ProjectionStub());
        $this->get('test projection')->name()->shouldBe('test projection');
    }
}
