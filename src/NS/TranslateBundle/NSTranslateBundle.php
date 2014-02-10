<?php

namespace NS\TranslateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSTranslateBundle extends Bundle
{
    public function getParent()
    {
        return 'JMSTranslationBundle';
    }
}
