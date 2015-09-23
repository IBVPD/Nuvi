<?php

namespace NS\SonataBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class NSSonataBundle
 * @package NS\SonataBundle
 */
class NSSonataBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'SonataAdminBundle';
    }
}
