<?php

namespace Blast\BaseEntitiesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Blast\CoreBundle\DependencyInjection\BlastCoreExtension;

class BlastBaseEntitiesExtension extends BlastCoreExtension
{
    private $entityManagers   = array();
    private $documentManagers = array();

    /**
     * {@inheritdoc}
     */
    public function loadListeners(ContainerBuilder $container, array $config)
    {
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
            $serviceId = 'blast_base_entities.listener.' . $listenerName;

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
            $container->getDefinition('blast_base_entities.listener.logger')
                ->setPublic(true)
                ->addTag('kernel.event_subscriber');
        }
        
        return $this;
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
