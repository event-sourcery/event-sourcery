<?php namespace EventSourcery\CommandDispatch;

class Mapper {

    public function handlerFor(Command $c) {
        $class = get_class($c) . 'Handler';
        if ( ! class_exists($class)) {
            throw new HandlerNotFound($class);
        }
        return $class;
    }
}
