<?php

namespace NS\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSApiBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSOAuthServerBundle';
    }
}
