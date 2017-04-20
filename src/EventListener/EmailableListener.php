<?php

/*
 * This file is part of the BLAST package <http://blast.libre-informatique.fr>.
 *
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blast\BaseEntitiesBundle\EventListener;

use Blast\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Blast\BaseEntitiesBundle\EventListener\Traits\Logger;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Log\LoggerAwareInterface;

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

        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Blast\BaseEntitiesBundle\Entity\Traits\Emailable'))
            return; // return if current entity doesn't use Emailable trait

        // Check if parents already have the Emailable trait
        foreach ($metadata->parentClasses as $parent)
            if ($this->classAnalyzer->hasTrait($parent, 'Blast\BaseEntitiesBundle\Entity\Traits\Emailable'))
                return;

        $this->logger->debug(
            "[EmailableListener] Entering EmailableListener for « loadClassMetadata » event"
        );

        // setting default mapping configuration for Emailable

        // email
        $metadata->mapField([
            'fieldName' => 'email',
            'type'      => 'string',
            'nullable'  => true,
        ]);

        // emailNpai
        $metadata->mapField([
            'fieldName' => 'emailNpai',
            'type'      => 'boolean',
            'nullable'  => true,
        ]);

        // emailNoNewsletter
        $metadata->mapField([
            'fieldName' => 'emailNoNewsletter',
            'type'      => 'boolean',
            'nullable'  => true,
        ]);

        $this->logger->debug(
            "[EmailableListener] Added Emailable mapping metadata to Entity",
            ['class' => $metadata->getName()]
        );

    }
}
