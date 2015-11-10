<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Psr\Log\LoggerAwareInterface;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;

class TreeableListener implements LoggerAwareInterface, EventSubscriber
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

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        if (!$this->hasTrait($metadata->getReflectionClass(), 'Knp\DoctrineBehaviors\Model\Tree\Node'))
            return; // return if current entity doesn't use Addressable trait

        $this->logger->debug(
            "[TreeableListener] Entering TreeableListener for « loadClassMetadata » event"
        );

        $metadata->setCustomRepositoryClass('Librinfo\BaseEntitiesBundle\Entity\Repository\TreeableRepository');

    }
}
