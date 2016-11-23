<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait Sortable
{
    /**
     * @var string
     */
    private $sortRank;

    /**
     * @param string $sortRank
     *
     * @return Sortable
     */
    public function setSortRank($sortRank)
    {
        $this->sortRank = $sortRank;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortRank()
    {
        return $this->sortRank;
    }
}
