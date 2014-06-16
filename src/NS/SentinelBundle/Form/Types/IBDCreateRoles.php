<?php

namespace NS\SentinelBundle\Form\Types;

use Symfony\Component\Form\AbstractType;

/**
 * Description of IBDCreateRoles
 *
 */
class IBDCreateRoles extends AbstractType
{
    public function getParent()
    {
        return 'CreateRoles';
    }

    public function getName()
    {
        return 'IBDCreateRoles';
    }
}
