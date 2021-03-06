<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\BaseEntitiesBundle\Loggable;

use Gedmo\Loggable\LoggableListener as BaseLoggableListener;

class LoggableListener extends BaseLoggableListener
{
    /**
     * in order to use the proper mapping driver, you have to keep the Mapping directory at the right place (relative to this namespace).
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }
}
