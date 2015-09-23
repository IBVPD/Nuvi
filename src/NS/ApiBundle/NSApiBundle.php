<?php

namespace NS\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class NSApiBundle
 * @package NS\ApiBundle
 */
class NSApiBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSOAuthServerBundle';
    }
}
