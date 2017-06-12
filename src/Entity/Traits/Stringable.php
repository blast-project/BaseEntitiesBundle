<?php

namespace Blast\BaseEntitiesBundle\Entity\Traits;

trait Stringable
{
    // @TODO: Set method name configurable
    public function __toString()
    {
        if ( method_exists(get_class($this), 'getName') )
            return (string)$this->getName();
        if ( method_exists(get_class($this), 'getId') )
            return (string)$this->getId();
        return '';
    }
}
