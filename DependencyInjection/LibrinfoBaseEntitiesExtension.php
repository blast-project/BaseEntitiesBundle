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

        $availableListeners = [
            'naming',
            'guidable',
            'traceable',
            'addressable',
            'treeable',
            'nameable',
            'labelable',
            'emailable',
            'descriptible',
            'searchable',
            //'loggable',
        ];

        foreach ( $availableListeners as $listenerName )
        {
            $serviceId = 'librinfo.base_entities.listener.' . $listenerName;

            // enable doctrine ORM event subscribers
            foreach ( $config['orm'] as $connection => $listeners ) {
                if ( !empty($listeners[$listenerName]) && $container->hasDefinition($serviceId) ) {
                    $definition = $container->getDefinition($serviceId);
                    $definition->addTag('doctrine.event_subscriber', ['connection' => $connection]);
                }
            }

            // enable doctrine ODM event subscribers
            // TODO : not tested
            foreach ( $config['mongodb'] as $connection => $listeners ) {
                if ( !empty($listeners['$listenerName']) && $container->hasDefinition($serviceId) ) {
                    $definition = $container->getDefinition($serviceId);
                    $definition->addTag('doctrine_mongodb.odm..event_subscriber', ['connection' => $connection]);
                }
            }
        }

        // Loading KnpDoctrineBehaviors services
        // TODO: remove this ???
        try
        {
            $KnpLoader = new Loader\YamlFileLoader($container, new FileLocator($container->getParameter('kernel.root_dir') . "/../vendor/knplabs/doctrine-behaviors/config/"));
            $KnpLoader->load('orm-services.yml');
        }
        catch (\Exception $e) { }

        // TODO: move this to new LibrinfoAdmin bundle
        $this->mergeParameter('librinfo', $container, __DIR__.'/../Resources/config');
    }
}
