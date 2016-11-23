<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerAwareInterface;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;

class DescriptibleListener implements LoggerAwareInterface, EventSubscriber
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
     * define Descriptible mapping at runtime
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Descriptible'))
            return; // return if current entity doesn't use Descriptible trait

        // Check if parents already have the Descriptible trait
        foreach ($metadata->parentClasses as $parent)
            if ($this->classAnalyzer->hasTrait($parent, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Descriptible'))
                return;

        $this->logger->debug("[DescriptibleListener] Entering DescriptibleListener for « loadClassMetadata » event");

        // setting default mapping configuration for Descriptible

        $metadata->mapField([
            'fieldName' => 'description',
            'type'      => 'text',
            'nullable'  => true,
        ]);

        $this->logger->debug(
            "[DescriptibleListener] Added Descriptible mapping metadata to Entity",
            ['class' => $metadata->getName()]
        );

    }
}
