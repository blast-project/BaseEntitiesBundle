<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait Guidable
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
