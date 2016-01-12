<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;
use Librinfo\BaseEntitiesBundle\Entity\SearchIndexEntity;

class SearchableListener implements EventSubscriber
{
    use ClassChecker,
        Logger;

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
        if (! $reflectionClass )
            return;

        // Add oneToMany mapping to entities that have the Searchable trait
        if ($this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Searchable'))
        {
            $this->logger->debug("[SearchableListener] Entering SearchableListener for « loadClassMetadata » event: entity " . $reflectionClass->getName());

            $metadata->mapOneToMany([
                'targetEntity' => $reflectionClass->getShortName() . 'SearchIndex',
                'fieldName' => 'searchIndexes',
                'mappedBy' => 'object',
                'cascade' => ['persist'],
            ]);
        }

        // Add manyToOne mapping to entities that exetend SearchIndexEntity (first parent only)
        $parentClass = $reflectionClass->getParentClass();
        if ( $parentClass && $parentClass->getName() ==  SearchIndexEntity::class )
        {
            $this->logger->debug("[SearchableListener] Entering SearchableListener for « loadClassMetadata » event: entity " . $reflectionClass->getName());

            $metadata->mapManyToOne([
                'targetEntity' => str_replace('SearchIndex', '', $reflectionClass->getName()),
                'fieldName' => 'object',
                'inversedBy' => 'searchIndexes',
                'cascade' => ['persist'],
            ]);
        }
    }


    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity)
            if ( $this->hasTrait($entity, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Searchable') )
            {
                $this->createSearchIndexes($em, $entity);
            }

        foreach ($uow->getScheduledEntityUpdates() as $entity)
            if ( $this->hasTrait($entity, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Searchable') )
            {
                $this->deleteSearchIndexes($em, $entity);
                $this->createSearchIndexes($em, $entity);
            }

        foreach ($uow->getScheduledEntityDeletions() as $entity)
            if ( $this->hasTrait($entity, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Searchable') )
            {
                $this->deleteSearchIndexes($em, $entity);
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
        $indexClass = $reflClass->getName() . 'SearchIndex';
        $classMetadata = $em->getClassMetadata($indexClass);

        $fields = $indexClass::$fields;
        foreach ( $fields as $field )
        {
            $keywords = $entity->analyseField($field);
            foreach ( $keywords as $keyword )
            {
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
