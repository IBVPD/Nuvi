<?php

namespace NS\TranslateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class NSTranslateBundle
 * @package NS\TranslateBundle
 */
class NSTranslateBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'JMSTranslationBundle';
    }
}
