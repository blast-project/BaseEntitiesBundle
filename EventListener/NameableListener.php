<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerAwareInterface;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;

class NameableListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

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

    /**
     * define Nameable mapping at runtime
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Nameable'))
            return; // return if current entity doesn't use Nameable trait

        // Check if parents already have the Nameable trait
        foreach ($metadata->parentClasses as $parent)
            if ($this->classAnalyzer->hasTrait($parent, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Nameable'))
                return;

        $this->logger->debug("[NameableListener] Entering NameableListener for « loadClassMetadata » event");

        // setting default mapping configuration for Nameable

        // name
        $metadata->mapField([
            'fieldName' => 'name',
            'type'      => 'string',
            //'nullable'  => true,
        ]);

        $this->logger->debug(
            "[NameableListener] Added Nameable mapping metadata to Entity",
            ['class' => $metadata->getName()]
        );

    }
}
