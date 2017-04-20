<?php

namespace Blast\BaseEntitiesBundle\Entity\Traits;

trait Guidable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return object  this Entity
     */
    public function setId($id)
    {
        $this->$id = $id;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isNew()
    {
        return $this->id ? false : true;
    }
}
