<?php

namespace Blast\BaseEntitiesBundle\Entity\Traits;

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

    public function __toString()
    {
        // @TODO: should use Stringable from traits
        return (string) ($this->label ? $this->label : (method_exists($this, 'getId') ? $this->getId() : ''));
    }
}
