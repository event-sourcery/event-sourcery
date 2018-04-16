<?php namespace EventSourcery\Money;

class CurrenciesDontMatch extends \Exception {
    public function __construct(Currency $first, Currency $second) {
        parent::__construct("Currencies [{$first}] and [{$second}] don't match.");
    }
}