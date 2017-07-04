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

namespace Blast\BaseEntitiesBundle\Entity\Traits;

trait Stringable
{
    // @TODO: Set method name configurable
    public function __toString()
    {
        if (method_exists(get_class($this), 'getName')) {
            return (string) $this->getName();
        }
        if (method_exists(get_class($this), 'getId')) {
            return (string) $this->getId();
        }

        return '';
    }
}
