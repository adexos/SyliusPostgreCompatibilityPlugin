<?php declare(strict_types=1);
/**
 * This file is part of the Adexos package.
 * (c) Adexos <contact@adexos.fr>
 */

namespace Adexos\SyliusPostgreCompatibilityPlugin\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RemoveSyliusDoctrineMigrationPass implements CompilerPassInterface
{
    public $defaultDisallowedNamespaces = [
        'Sylius\PayPalPlugin\Migrations',
        'Sylius\Bundle\CoreBundle\Migrations'
    ];

    public function process(ContainerBuilder $container): void
    {
        $bundleConfig = $container->getParameter('adexos_sylius_postgre_compatibility_plugin');
        $disallowedNamespaces = $this->prepareExcludedNamespaces($bundleConfig);

        if ( ! $container->hasDefinition('doctrine.migrations.configuration')) {
            return;
        }

        $definition = $container->getDefinition('doctrine.migrations.configuration');
        $calls = $definition->getMethodCalls();
        $sanitizedMethodCalls = [];

        /** @var string $call */
        foreach ($calls as $call) {
            if ('addMigrationsDirectory' !== $call[0]) {
                $sanitizedMethodCalls[] = $call;

                continue;
            }

            if (!in_array($call[1][0], $disallowedNamespaces, true)) {
                $sanitizedMethodCalls[] = $call;
            }
        }

        $definition->setMethodCalls($sanitizedMethodCalls);
    }

    protected function prepareExcludedNamespaces(array $bundleConfig): array
    {
        $disallowedNamespaces = $bundleConfig['excluded_migration_namespaces'];

        return array_merge($disallowedNamespaces, $this->defaultDisallowedNamespaces);
    }
}
