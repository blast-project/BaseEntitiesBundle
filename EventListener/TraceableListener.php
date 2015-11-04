<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Loggable\LoggableListener;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class TraceableListener implements LoggerAwareInterface, EventSubscriber
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata',
            'prePersist',
            'preUpdate'
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy();

        if (!array_key_exists('Librinfo\BaseEntitiesBundle\Entity\Interfaces\TraceableInterface', $metadata->getReflectionClass()->getInterfaces()))
            return;

        var_dump($metadata);

        $metadata->mapField([
            'fieldName' => 'createdDate',
            'type'      => 'datetime'
        ]);

        $metadata->mapField([
            'fieldName' => 'lastUpdateDate',
            'type'      => 'datetime'
        ]);

//        $metadata->mapField([
//            'fieldName' => 'name',
//            'type'      => 'string',
//            'length'    => 255
//        ]);

//        address:
//            type:       string
//            nullable:   true
//        postalcode:
//            type:       string(10)
//            nullable:   true
//        city:
//            type:       string(255)
//            nullable:   true
//        country:
//            type:       string(255)
//            nullable:   true
//            option:     { default: FRANCE }
//        npai:
//            type:       boolean
//            nullable:   true
//            options:    { default: false }
//        email:
//            type:       string(255)
//            nullable:   true
//        emailNpai:
//            type:       boolean
//            nullable:   true
//            options:    { default: false }
//        emailNoNewsletter:
//            type:       boolean
//            nullable:   true
//            options:    { default: false }
//        description:
//            type:       string
//            nullable:   true
//        vcardUid:
//            type:       string(255)
//            nullable:   true
//        confirmed:
//            type:       boolean
//            nullable:   true
//            options:    { default: true }

//        $metadata->mapManyToOne([
//            'targetEntity' => $metadata->getReflectionClass()->getName(),
//            'fieldName'    => 'parent',
//            'inversedBy'   => 'children',
//            'joinColumn'   => [
//                'name'                 => 'parent_id',
//                'referencedColumnName' => 'id',
//                'onDelete'             => 'CASCADE'
//            ],
//            'gedmo'        => [
//                'treeParent'
//            ]
//        ]);
//
//        $metadata->mapOneToMany([
//            'targetEntity' => $metadata->getReflectionClass()->getName(),
//            'fieldName'    => 'children',
//            'mappedBy'     => 'parent',
//            'orderBy'      => [
//                'lft' => 'ASC'
//            ]
//        ]);

//        $evm = new \Doctrine\Common\EventManager();
//        $treeListener = new \Gedmo\Tree\TreeListener();
//        $evm->addEventSubscriber($treeListener);

    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {

    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {

    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

}
