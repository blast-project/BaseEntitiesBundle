<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use Librinfo\BaseEntitiesBundle\Entity\Traits\Traceable;

trait BaseEntity
{
    use Idable;
    
    public function __toString()
    {
        if ( method_exists(get_class($this), 'getName') )
            return (string)$this->getName();
        if ( method_exists(get_class($this), 'getId') )
            return (string)$this->getId();
        return '';
    }
}
