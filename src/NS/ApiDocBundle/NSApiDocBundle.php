<?php

namespace NS\ApiDocBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Description of NSApiDocBundle
 *
 * @author gnat
 */
class NSApiDocBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'NelmioApiDocBundle';
    }
}
