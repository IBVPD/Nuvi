<?php

namespace NS\SentinelBundle\Services;

use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;

/**
 * Description of Serialization
 *
 * @author gnat
 */
class Serialization
{
    public function serializeArrayChoiceToJson(JsonSerializationVisitor $visitor, $obj, array $type, SerializationContext $context)
    {
        return $obj->getValue();
    }
}
