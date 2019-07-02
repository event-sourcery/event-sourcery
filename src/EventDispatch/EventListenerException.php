<?php namespace EventSourcery\EventSourcery\EventDispatch;

use Exception;

class EventListenerException extends \Exception
{
    /** @var \Exception */
    private $exception;

    public function __construct(\Exception $e)
    {
        $this->exception = $e;
        parent::__construct($e);
    }

    public function getException(): Exception
    {
        return $this->exception;
    }
}