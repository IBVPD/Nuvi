<?php

namespace NS\SentinelBundle\Form\Types;

/**
 * Description of IBDCreateRoles
 *
 */
class IBDCreateRoles extends CreateRoles
{
    const ROUTE_BASE = 'ibd';

    public function getParent()
    {
        return 'CreateRoles';
    }

    public function getName()
    {
        return 'IBDCreateRoles';
    }
}
