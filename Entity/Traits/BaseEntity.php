<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use Librinfo\BaseEntitiesBundle\Entity\Traits\Traceable;

trait BaseEntity
{
    /**
     * @var string
     */
    protected $id;

//    use Traceable;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {

        if (method_exists(get_class($this), 'getId'))
            return (string)$this->getId();
        if (method_exists(get_class($this), 'getName'))
            return (string)$this->getName();

        return '';
    }
}
