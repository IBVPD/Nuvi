<?php

namespace NS\ImportBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use NS\ImportBundle\DependencyInjection\Compiler\ImportConverterCompilerPass;

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
