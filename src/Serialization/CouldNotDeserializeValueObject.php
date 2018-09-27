<?php namespace EventSourcery\EventSourcery\Serialization;

final class CouldNotDeserializeValueObject extends SerializationException {

    function __construct(\TypeError $previousException) {
        $message = <<<MESSAGE
Original exception: "{$previousException->getMessage()}"

Most deserialization errors are caused when `deserialize()` isn't called for nested value objects during deserialization.

A correct example:

public static function deserialize(array \$data) {
    return new static(\NestedValueObject::deserialize(\$data['string1']), \$data['string2']);
}
MESSAGE;
        parent::__construct($message, 0, $previousException);
    }
}