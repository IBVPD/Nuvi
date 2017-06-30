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
        if (false === $container->hasDefinition('ns_import.converters')) {
            return;
        }

        $definition = $container->getDefinition('ns_import.converters');
        $calls = $definition->getMethodCalls();
        $definition->setMethodCalls([]);

        foreach ($container->findTaggedServiceIds('ns_import.converter') as $id => $attributes) {
            $definition->addMethodCall('addConverter', [strtolower($id), new Reference($id)]);
        }

        $definition->setMethodCalls(array_merge($definition->getMethodCalls(), $calls));
    }
}
