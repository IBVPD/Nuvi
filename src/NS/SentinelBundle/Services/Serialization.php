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
    /**
     * @param JsonSerializationVisitor $visitor
     * @param type $obj
     * @param array $type
     * @param SerializationContext $context
     * @return integer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function serializeArrayChoiceToJson(JsonSerializationVisitor $visitor, $obj, array $type, SerializationContext $context)
    {
        return $obj->getValue();
    }
}
