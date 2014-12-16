<?php

namespace NS\SentinelBundle\Form\Types;

/**
 * Description of IBDCreateRoles
 *
 */
class IBDCreateRoles extends CreateRoles
{
    const ROUTE_BASE = 'ibd';

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'CreateRoles';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'IBDCreateRoles';
    }
}
