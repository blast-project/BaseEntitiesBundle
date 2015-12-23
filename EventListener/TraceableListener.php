<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;

class TraceableListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var string
     */
    private $userClass;

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
            'preUpdate'
        ];
    }

    /**
     * define Traceable mapping at runtime
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Traceable'))
            return; // return if current entity doesn't use Traceable trait

        $this->logger->debug(
            "[TraceableListener] Entering TraceableListener for « loadClassMetadata » event"
        );

        $this->logger->debug(
            "[TraceableListener] Using « " . $this->userClass . " » as User class"
        );

        // setting default mapping configuration for Traceable

        // createdAt
        $metadata->mapField([
            'fieldName' => 'createdAt',
            'type'      => 'datetime',
            'nullable'  => true
        ]);

        // updatedAt
        $metadata->mapField([
            'fieldName' => 'updatedAt',
            'type'      => 'datetime',
            'nullable'  => true
        ]);

        $this->logger->debug(
            "[TraceableListener] Added Traceable mapping metadata to Entity",
            ['class' => $metadata->getName()]
        );
    }

    /**
     * sets Traceable dateTime and user information when persisting entity
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$this->hasTrait($entity, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Traceable'))
            return;

        $this->logger->debug(
            "[TraceableListener] Entering TraceableListener for « prePersist » event"
        );

        $now = new DateTime('NOW');
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);
    }

    /**
     * sets Traceable dateTime and user information when updating entity
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$this->hasTrait($entity, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Traceable'))
            return;

        $this->logger->debug(
            "[TraceableListener] Entering TraceableListener for « preUpdate » event"
        );

        $now = new DateTime('NOW');
        $entity->setUpdatedAt($now);
    }

    /**
     * setTokenStorage
     *
     * @param TokenStorage $tokenStorage
     */
    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $userClass
     */
    public function setUserClass($userClass)
    {
        $this->userClass = $userClass;
    }
}
