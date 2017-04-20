<?php

namespace Blast\BaseEntitiesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ValidateExtensionConfigurationPass implements CompilerPassInterface
{
    /**
     * Validate the BlastBaseEntitiesBundle DIC extension config.
     *
     * This validation runs in a discrete compiler pass because it depends on
     * DBAL and ODM services, which aren't available during the config merge
     * compiler pass.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->getExtension('blast_base_entities')->configValidate($container);
    }
}
