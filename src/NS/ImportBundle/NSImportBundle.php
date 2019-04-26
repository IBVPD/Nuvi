<?php

namespace NS\ImportBundle;

use NS\ImportBundle\DependencyInjection\Compiler\ImportConverterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class NSImportBundle
 * @package NS\ImportBundle
 */
class NSImportBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ImportConverterCompilerPass());
    }
}
