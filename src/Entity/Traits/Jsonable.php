<?php

namespace Blast\BaseEntitiesBundle\Entity\Traits;

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