<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;

class AddressableListener implements LoggerAwareInterface, EventSubscriber
{
    /**
     * @var Logger
     */
    private $logger;

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
            "[AddressableListner] Entering AddressableListner for « loadClassMetadata » event"
        );

        // setting default mapping configuration for Traceable

        // addressName
        $metadata->mapField([
            'fieldName' => 'addressName',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // addressDescription
        $metadata->mapField([
            'fieldName' => 'addressDescription',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // address
        $metadata->mapField([
            'fieldName' => 'address',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // postalCode
        $metadata->mapField([
            'fieldName' => 'postalCode',
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

        // vcardUid
        $metadata->mapField([
            'fieldName' => 'vcardUid',
            'type'      => 'string',
            'nullable'  => true
        ]);

        // addressConfirmed
        $metadata->mapField([
            'fieldName' => 'addressConfirmed',
            'type'      => 'boolean',
            'nullable'  => true
        ]);

        $this->logger->debug(
            "[AddressableListner] Added Addressable mapping metadata to Entity",
            ['class' => $metadata->getName()]
        );

    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
