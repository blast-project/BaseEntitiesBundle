<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait Nameable
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @param string $name
     *
     * @return Addressable
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
