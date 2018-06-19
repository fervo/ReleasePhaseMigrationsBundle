<?php

namespace Fervo\ReleasePhaseMigrationsBundle\DependencyInjection;

use Fervo\ReleasePhaseMigrationsBundle\Command\MigrateCommand;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class FervoReleasePhaseMigrationsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $definition = new Definition(MigrateCommand::class);
        $definition->setTags([
            'console.command' => [
                [],
            ],
        ]);

        $container->setDefinition('fervo_release_phase_migrations.migrate_command', $definition);
    }
}
