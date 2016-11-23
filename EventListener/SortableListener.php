<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerAwareInterface;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;
use Librinfo\BaseEntitiesBundle\Entity\Repository\SortableRepository;

class SortableListener implements LoggerAwareInterface, EventSubscriber
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
            'prePersist',
        ];
    }

    /**
     * define Sortable mapping at runtime
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();

        if ( !$reflectionClass || !$this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Sortable') )
            return; // return if current entity doesn't use Sortable trait

        $this->logger->debug("[SortableListener] Entering SortableListener for « loadClassMetadata » event");

        // setting default mapping configuration for Sortable
        // sortRank
        $metadata->mapField([
            'fieldName' => 'sortRank',
            'type' => 'float',
            'nullable' => false,
            'default' => 65536
        ]);

        // add index on sort_rank column
        if ( !isset($metadata->table['indexes']) )
            $metadata->table['indexes'] = [];
        $metadata->table['indexes']['sort_rank'] = ['columns' => ['sort_rank']];

        $this->logger->debug(
            "[SortableListener] Added Sortable mapping metadata to Entity", ['class' => $metadata->getName()]
        );
    }

    /**
     * Compute sortRank for entities that are created
     *
     * @param EventArgs $args
     */
    public function prePersist(EventArgs $args)
    {
        $em = $args->getEntityManager();
        $object = $args->getObject();
        $class = get_class($object);
        $meta = $em->getClassMetadata($class);

        $reflectionClass = $meta->getReflectionClass();

        if ( !$reflectionClass || !$this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Sortable') )
            return; // return if current entity doesn't use Sortable trait

        if ( $object->getSortRank() )
            return;

        $maxPos = $this->getMaxPosition($em, $meta);
        $maxPos = $maxPos ? $maxPos + 1000 : 65536;

        $object->setSortRank($maxPos);
        $this->maxPositions[$class] = $maxPos;
    }

    private function getMaxPosition($em, $meta)
    {
        $class = $meta->name;
        if ( isset($this->maxPositions[$class]) )
            return $this->maxPositions[$class];

        $repo = new SortableRepository($em, $meta);
        return $repo->getMaxPosition();
    }

}
