<?php

namespace Librinfo\BaseEntitiesBundle\Loggable;

use Gedmo\Loggable\LoggableListener as BaseLoggableListener;

class LoggableListener extends BaseLoggableListener
{
    /**
     * {@inheritDoc}
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

}
