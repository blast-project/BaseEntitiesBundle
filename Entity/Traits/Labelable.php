<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait Labelable
{
    /**
     * @var string
     */
    private $label;

    /**
     * @param string $label
     *
     * @return Addressable
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
