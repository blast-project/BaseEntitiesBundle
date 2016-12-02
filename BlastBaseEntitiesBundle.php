<?php

namespace Blast\BaseEntitiesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Blast\BaseEntitiesBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;

class BlastBaseEntitiesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
    }
}
