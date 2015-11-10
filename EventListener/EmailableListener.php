<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;

class EmailableListener implements LoggerAwareInterface, EventSubscriber
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
     * define Emailable mapping at runtime
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        if (!$this->hasTrait($metadata->getReflectionClass(), 'Librinfo\BaseEntitiesBundle\Entity\Traits\Emailable'))
            return; // return if current entity doesn't use Emailable trait

        $this->logger->debug(
            "[EmailableListener] Entering EmailableListener for « loadClassMetadata » event"
        );

        // setting default mapping configuration for Traceable

        // email
        $metadata->mapField([
            'fieldName' => 'email',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // emailNpai
        $metadata->mapField([
            'fieldName' => 'emailNpai',
            'type'      => 'boolean',
            'nullable'  => true
        ]);

        // emailNoNewsletter
        $metadata->mapField([
            'fieldName' => 'emailNoNewsletter',
            'type'      => 'boolean',
            'nullable'  => true
        ]);

        $this->logger->debug(
            "[EmailableListener] Added Emailable mapping metadata to Entity",
            ['class' => $metadata->getName()]
        );

    }
}
