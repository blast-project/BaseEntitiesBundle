<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Blast\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\BaseEntitiesBundle\EventListener\Traits\Logger;

class SearchableListener implements EventSubscriber
{
    use ClassChecker,
        Logger;

    /**
     * @var array
     */
    private $classFields;

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata',
            //'prePersist',
            //'preUpdate',
            //'preFlush',
            'onFlush',
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();
        if (!$reflectionClass) {
            return;
        }

        // Check if parents already have the Searchable trait
        foreach ($metadata->parentClasses as $parent) {
            if ($this->classAnalyzer->hasTrait($parent, 'Blast\BaseEntitiesBundle\Entity\Traits\Searchable')) {
                return;
            }
        }

        // Add oneToMany mapping to entities that have the Searchable trait
        if ($this->hasTrait($reflectionClass, 'Blast\BaseEntitiesBundle\Entity\Traits\Searchable')) {
            $this->logger->debug('[SearchableListener] Entering SearchableListener for « loadClassMetadata » event: entity ' . $reflectionClass->getName());

            $metadata->mapOneToMany([
                'targetEntity' => $reflectionClass->getShortName() . 'SearchIndex',
                'fieldName'    => 'searchIndexes',
                'mappedBy'     => 'object',
                'cascade'      => ['persist'],
            ]);
        }

        // Add manyToOne mapping to entities that extend SearchIndexEntity (first parent only)
        $parentClass = $reflectionClass->getParentClass();
        if ($parentClass && $parentClass->getName() == 'Blast\BaseEntitiesBundle\Entity\SearchIndexEntity') {
            $this->logger->debug('[SearchableListener] Entering SearchableListener for « loadClassMetadata » event: entity ' . $reflectionClass->getName());

            $metadata->mapManyToOne([
                'targetEntity' => str_replace('SearchIndex', '', $reflectionClass->getName()),
                'fieldName'    => 'object',
                'inversedBy'   => 'searchIndexes',
                'cascade'      => ['persist'],
            ]);
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($this->hasTrait($entity, 'Blast\BaseEntitiesBundle\Entity\Traits\Searchable')) {
                $this->createSearchIndexes($em, $entity);
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($this->hasTrait($entity, 'Blast\BaseEntitiesBundle\Entity\Traits\Searchable')) {
                $this->deleteSearchIndexes($em, $entity);
                $this->createSearchIndexes($em, $entity);
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($this->hasTrait($entity, 'Blast\BaseEntitiesBundle\Entity\Traits\Searchable')) {
                $this->deleteSearchIndexes($em, $entity);
            }
        }
    }

    private function deleteSearchIndexes($em, $entity)
    {
        $reflClass = new \ReflectionClass($entity);
        $indexClass = $reflClass->getName() . 'SearchIndex';

        // delete old keywords
        $sql = "DELETE $indexClass p WHERE p.object = :entity";
        $query = $em->createQuery($sql)->setParameter('entity', $entity);
        $query->execute();
    }

    private function createSearchIndexes($em, $entity)
    {
        $uow = $em->getUnitOfWork();
        $reflClass = new \ReflectionClass($entity);
        $entityClass = $reflClass->getName();
        $indexClass = $entityClass . 'SearchIndex';
        $classMetadata = $em->getClassMetadata($indexClass);

        if (array_key_exists($entityClass, $this->classFields)) {
            foreach ($this->classFields[$entityClass] as $field) {
                if (!is_array($field)) {
                    $keywords = $entity->analyseField($field);
                    foreach ($keywords as $keyword) {
                        $index = new $indexClass();
                        $index->setObject($entity);
                        $index->setField($field);
                        $index->setKeyword($keyword);
                        $em->persist($index);
                        $uow->computeChangeSet($classMetadata, $index);
                    }
                }
            }
        }
    }

    public function setClassFields(array $classFields)
    {
        $this->classFields = $classFields;
    }
}
