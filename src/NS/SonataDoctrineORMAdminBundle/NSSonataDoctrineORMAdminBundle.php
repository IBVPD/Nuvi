<?php

namespace NS\SonataDoctrineORMAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSSonataDoctrineORMAdminBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'SonataDoctrineORMAdminBundle';
    }
}

