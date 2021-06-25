<?php declare(strict_types=1);
/**
 * This file is part of the Adexos package.
 * (c) Adexos <contact@adexos.fr>
 */

namespace Adexos\SyliusPostgreCompatibilityPlugin\DependencyInjection\CompilerPass;

use Adexos\SyliusPostgreCompatibilityPlugin\DataProvider\SalesDataProvider;
use Sylius\Component\Core\Dashboard\SalesDataProviderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition(SalesDataProviderInterface::class);
        $definition->setClass(SalesDataProvider::class);
        $container->setDefinition(SalesDataProviderInterface::class, $definition);
    }
}
