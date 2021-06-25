<?php
/**
 * This file is part of the Adexos package.
 * (c) Adexos <contact@adexos.fr>
 */

namespace Adexos\SyliusPostgreCompatibilityPlugin;

use Adexos\SyliusPostgreCompatibilityPlugin\DependencyInjection\CompilerPass\OverrideServiceCompilerPass;
use Adexos\SyliusPostgreCompatibilityPlugin\DependencyInjection\RemoveSyliusDoctrineMigrationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SyliusPostgreCompatibilityPlugin extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RemoveSyliusDoctrineMigrationPass([
            'Sylius\PayPalPlugin\Migrations',
            'Sylius\Bundle\CoreBundle\Migrations'
        ]));
        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}
