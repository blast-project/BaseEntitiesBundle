<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
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
            'loadClassMetadata'
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
}
