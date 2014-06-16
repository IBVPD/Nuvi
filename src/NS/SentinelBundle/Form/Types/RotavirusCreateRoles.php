<?php

namespace NS\SentinelBundle\Form\Types;

use Symfony\Component\Form\AbstractType;

/**
 * Description of RotaCreateRoles
 *
 * @author gnat
 */
class RotavirusCreateRoles extends AbstractType
{
    public function getParent()
    {
        return 'CreateRoles';
    }

    public function getName()
    {
        return 'RotavirusCreateRoles';
    }
}
