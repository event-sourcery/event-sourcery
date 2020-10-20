<?php namespace EventSourcery\EventSourcery\Serialization;

final class CouldNotDeserializeDomainEvent extends SerializationException {
    public static function noEventNameSpecified(string $json) {
        return new static($json);
    }

    public static function requiredConstructorTypeIsNotPresent(string $className, array $parameterArray) {
        return new static(
            "Class {$className} is missing a necessary constructor type declaration. Parameters include: " . implode(', ', $parameterArray)
        );
    }
}