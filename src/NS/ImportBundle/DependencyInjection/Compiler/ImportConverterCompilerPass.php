<?php

namespace NS\ImportBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of ImportConverterCompilerPass
 *
 * @author gnat
 */
class ImportConverterCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('ns_import.converters'))
            return;

        $definition = $container->getDefinition('ns_import.converters');

        // Extensions must always be registered before everything else.
        // For instance, global variable definitions must be registered
        // afterward. If not, the globals from the extensions will never
        // be registered.
        $calls = $definition->getMethodCalls();
        $definition->setMethodCalls(array());

        foreach ($container->findTaggedServiceIds('ns_import.converter') as $id => $attributes)
            $definition->addMethodCall('addConverter', array($id,new Reference($id)));

        $definition->setMethodCalls(array_merge($definition->getMethodCalls(), $calls));
    }
}
