<?php

namespace Blast\BaseEntitiesBundle\Entity\Traits;

trait Descriptible
{
    /**
     * @var string
     */
    private $description;

    /**
     * @param string $description
     *
     * @return Addressable
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
