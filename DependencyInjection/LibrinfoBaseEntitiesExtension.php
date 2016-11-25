<?php

namespace Librinfo\BaseEntitiesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Blast\CoreBundle\DependencyInjection\BlastCoreExtension;

class LibrinfoBaseEntitiesExtension extends BlastCoreExtension
{
    private $entityManagers   = array();
    private $documentManagers = array();

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
            'nested_treeable',
            'nameable',
            'labelable',
            'emailable',
            'descriptible',
            'searchable',
            'loggable',
            'sortable',
            'normalize',
            'syliusGuidable',
        ];

        $useLoggable = false;

        foreach ( $availableListeners as $listenerName )
        {
            $serviceId = 'librinfo.base_entities.listener.' . $listenerName;

            // enable doctrine ORM event subscribers
            foreach ( $config['orm'] as $connection => $listeners ) {
                if ( !empty($listeners[$listenerName]) && $container->hasDefinition($serviceId) ) {
                    $definition = $container->getDefinition($serviceId);
                    $definition->addTag('doctrine.event_subscriber', ['connection' => $connection]);
                    if ( $listenerName == 'loggable' )
                        $useLoggable = true;
                }
                $this->entityManagers[$connection] = $listeners;
            }

            // enable doctrine ODM event subscribers
            // TODO : not tested
            foreach ( $config['mongodb'] as $connection => $listeners ) {
                if ( !empty($listeners['$listenerName']) && $container->hasDefinition($serviceId) ) {
                    $definition = $container->getDefinition($serviceId);
                    $definition->addTag('doctrine_mongodb.odm..event_subscriber', ['connection' => $connection]);
                    if ( $listenerName == 'loggable' )
                        $useLoggable = true;
                }
                $this->documentManagers[$connection] = $listeners;
            }
        }

        if ( $useLoggable )
        {
            $container->getDefinition('librinfo.base_entities.listener.logger')
                ->setPublic(true)
                ->addTag('kernel.event_subscriber');
        }
    }

    public function configValidate(ContainerBuilder $container)
    {
        foreach (array_keys($this->entityManagers) as $name) {
            if (!$container->hasDefinition(sprintf('doctrine.dbal.%s_connection', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: DBAL connection "%s" not found', $this->getAlias(), $name));
            }
        }

        foreach (array_keys($this->documentManagers) as $name) {
            if (!$container->hasDefinition(sprintf('doctrine_mongodb.odm.%s_document_manager', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: document manager "%s" not found', $this->getAlias(), $name));
            }
        }
    }
}
