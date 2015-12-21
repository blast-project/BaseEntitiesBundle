<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use Librinfo\BaseEntitiesBundle\Entity\Traits\Traceable;

trait Idable
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
}
