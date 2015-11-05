<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Knp\DoctrineBehaviors\ORM\Tree\TreeSubscriber;
use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;

class MappingListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {

        $eventArgs->getEntityManager()->getEventManager()->addEventSubscriber(
            new TreeSubscriber(new ClassAnalyzer(), true, 'Librinfo\BaseEntitiesBundle\Entity\Traits\TreeEntity')
        );

        $metadata = $eventArgs->getClassMetadata();
        $namingStrategy = $eventArgs
            ->getEntityManager()
            ->getConfiguration()
            ->getNamingStrategy();

        if ($namingStrategy->classToTableName($metadata->getName()) == $metadata->table['name'])
        {
            $newname = $metadata->name;
            $newname = str_replace('Bundle\\Entity', '', $newname);
            $newname = str_replace('\\', '_', $newname);
            $newname = strtolower($newname);
            $metadata->table['name'] = $newname;
        }


    }
}
