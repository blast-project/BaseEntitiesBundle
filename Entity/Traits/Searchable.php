<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Librinfo\BaseEntitiesBundle\Entity\SearchIndexEntity;
use Librinfo\BaseEntitiesBundle\SearchAnalyser\SearchAnalyser;

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

    /**
     * @param string $field
     * @return array
     * @throws \Exception
     */
    public function analyseField($field)
    {
        if (!isset($this->$field))
            throw new \Exception("Property $field does not exist.");
        return SearchAnalyser::analyse($this->$field);
    }
}
