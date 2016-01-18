<?php

namespace Librinfo\BaseEntitiesBundle\Loggable;

use Gedmo\Loggable\LoggableListener as BaseLoggableListener;

class LoggableListener extends BaseLoggableListener
{
    /**
     * in order to use the proper mapping driver, you have to keep the Mapping directory at the right place (relative to this namespace)
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

}
