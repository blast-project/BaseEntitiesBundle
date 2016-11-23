<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

/**
 * Default implementation of JsonSerializable::jsonSerialize()
 */
trait Jsonable
{
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}