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
    private array $disallowedNamespaces;

    public function __construct(array $disallowedNamespaces = [])
    {
        $this->disallowedNamespaces = $disallowedNamespaces;
    }

    public function process(ContainerBuilder $container): void
    {
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

            if (!in_array($call[1][0], $this->disallowedNamespaces, true)) {
                $sanitizedMethodCalls[] = $call;
            }
        }

        $definition->setMethodCalls($sanitizedMethodCalls);
    }
}
