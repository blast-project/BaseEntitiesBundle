<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class TreeableListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy();

//        var_dump($metadata->getReflectionClass()->getInterfaces());
        if (!array_key_exists('Librinfo\BaseEntitiesBundle\Entity\TreeableInterface', $metadata->getReflectionClass()->getInterfaces()))
            return;

//        var_dump($metadata);

        $metadata->setCustomRepositoryClass('Gedmo\Tree\Entity\Repository\NestedTreeRepository');

//        $metadata->mapField([
//            'fieldName' => 'lft',
//            'type'      => 'integer'
//        ]);
//
//        $metadata->mapField([
//            'fieldName' => 'rgt',
//            'type'      => 'integer'
//        ]);
//
//        $metadata->mapField([
//            'fieldName' => 'root',
//            'type'      => 'integer'
//        ]);
//
//        $metadata->mapField([
//            'fieldName' => 'lvl',
//            'type'      => 'integer'
//        ]);
//
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
}
