<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Librinfo\BaseEntitiesBundle\Entity\SearchIndexEntity;

trait Searchable
{
    /**
     * @var ArrayCollection
     */
    protected $searchIndexes;

    public function __construct() {
        $this->searchIndexes = new ArrayCollection();
    }

    public function addSearchIndex(SearchIndexEntity $searchIndex)
    {
        $this->searchIndexes->add($searchIndex);

        return $this;
    }

    public function removeSearchIndex(SearchIndexEntity $searchIndex)
    {
        $this->searchIndexes->removeElement($searchIndex);
    }

    public function getSearchIndexes()
    {
        return $this->searchIndexes;
    }

    public function setSearchIndexes(ArrayCollection $searchIndexes)
    {
        $this->searchIndexes = $searchIndexes;

        return $this;
    }
}
