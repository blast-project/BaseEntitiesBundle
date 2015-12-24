<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Id\UuidGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;

class MappingListener implements EventSubscriber
{
    use ClassChecker;

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

        $namingStrategy = $eventArgs
                ->getEntityManager()
                ->getConfiguration()
                ->getNamingStrategy();

        if ( $namingStrategy->classToTableName($metadata->getName()) == $metadata->table['name'] )
        {
            $newname = $metadata->name;
            $newname = str_replace('Bundle\\Entity', '', $newname);
            $newname = str_replace('\\', '_', $newname);
            $newname = strtolower($newname);
            $metadata->table['name'] = $newname;
        }

        // Do not generate id mapping twice for entities that extend a MappedSuperclass
        if ($metadata->isMappedSuperclass)
            return;

        $reflectionClass = $metadata->getReflectionClass();

        if ( !$reflectionClass || !$this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Idable') )
        {
            return;
        } // return if current entity doesn't use Idable trait

        $metadata->mapField([
            'id' => true,
            'fieldName' => "id",
            'type' => "guid",
            'columnName' => "id",
        ]);
        $metadata->setIdGenerator(new UuidGenerator());
    }
}
