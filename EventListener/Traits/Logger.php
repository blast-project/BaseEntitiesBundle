<?php

namespace Librinfo\BaseEntitiesBundle\EventListener\Traits;

use Psr\Log\LoggerInterface;

trait Logger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

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
