<?php

namespace Librinfo\BaseEntitiesBundle\EventListener\Traits;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Monolog\Logger;

trait ClassChecker
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
