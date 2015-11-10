<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerAwareInterface;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;

class AddressableListener implements LoggerAwareInterface, EventSubscriber
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
     * define Addressable mapping at runtime
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        if (!$this->hasTrait($metadata->getReflectionClass(), 'Librinfo\BaseEntitiesBundle\Entity\Traits\Addressable'))
            return; // return if current entity doesn't use Addressable trait

        $this->logger->debug(
            "[AddressableListener] Entering AddressableListener for « loadClassMetadata » event"
        );

        // setting default mapping configuration for Traceable

        // addressDescription
        $metadata->mapField([
            'fieldName' => 'description',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // address
        $metadata->mapField([
            'fieldName' => 'address',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // zip
        $metadata->mapField([
            'fieldName' => 'zip',
            'type'      => 'string',
            'length'    => 5,
            'nullable'  => true
        ]);

        // city
        $metadata->mapField([
            'fieldName' => 'city',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // country
        $metadata->mapField([
            'fieldName' => 'country',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // npai
        $metadata->mapField([
            'fieldName' => 'npai',
            'type'      => 'boolean',
            'nullable'  => true
        ]);

        // vcardUid
        $metadata->mapField([
            'fieldName' => 'vcardUid',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // confirmed
        $metadata->mapField([
            'fieldName' => 'confirmed',
            'type'      => 'boolean',
            'default'   => true,
        ]);

        $this->logger->debug(
            "[AddressableListener] Added Addressable mapping metadata to Entity",
            ['class' => $metadata->getName()]
        );

    }
}
