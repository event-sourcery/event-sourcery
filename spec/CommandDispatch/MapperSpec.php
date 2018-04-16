<?php namespace spec\EventSourcery\CommandDispatch;

use EventSourcery\CommandDispatch\Command;
use EventSourcery\CommandDispatch\CommandHandler;
use EventSourcery\CommandDispatch\HandlerNotFound;
use EventSourcery\CommandDispatch\Mapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MapperSpec extends ObjectBehavior {
    function it_is_initializable() {
        $this->shouldHaveType(Mapper::class);
    }
    function it_determines_handler_classes_for_commands() {
        $this->handlerFor(new CommandWithHandler)->shouldBe(CommandWithHandlerHandler::class);
    }
    function it_throws_an_exception_when_the_handler_is_not_found() {
        $this->shouldThrow(HandlerNotFound::class)
            ->during('handlerFor', [new CommandWithoutHandler]);
    }
}

class CommandWithoutHandler implements Command {}
class CommandWithHandler implements Command {}
class CommandWithHandlerHandler implements CommandHandler {

    public function handle($command) {

    }
}
