<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class MappingListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy()
        ;
        
        if ( $namingStrategy->classToTableName($metadata->getName()) == $metadata->table['name'] )
        {
            $newname = $metadata->name;
            $newname = str_replace('Bundle\\Entity','',$newname);
            $newname = str_replace('\\', '_', $newname);
            $newname = strtolower($newname);
            $metadata->table['name'] = $newname;
        }
    }
}
