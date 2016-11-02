<?php

namespace Librinfo\BaseEntitiesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Librinfo\CoreBundle\DependencyInjection\LibrinfoCoreExtension;

class LibrinfoBaseEntitiesExtension extends LibrinfoCoreExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // Loading KnpDoctrineBehaviors services
        // TODO : do we really need this ? (use treeable from Gedmo Doctrine Extensions ?)
//        try
//        {
//            $KnpLoader = new Loader\YamlFileLoader($container, new FileLocator($container->getParameter('kernel.root_dir') . "/../vendor/knplabs/doctrine-behaviors/config/"));
//            $KnpLoader->load('orm-services.yml');
//        }
//        catch (\Exception $e) { }

        $this->mergeParameter('librinfo', $container, __DIR__.'/../Resources/config');
    }
}
