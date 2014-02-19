<?php

namespace NS\SonataBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSSonataBundle extends Bundle
{
    public function getParent()
    {
        return 'SonataAdminBundle';
    }
}
