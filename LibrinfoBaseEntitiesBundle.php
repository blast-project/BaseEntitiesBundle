<?php

namespace Librinfo\BaseEntitiesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Librinfo\BaseEntitiesBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;

class LibrinfoBaseEntitiesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
    }
}
