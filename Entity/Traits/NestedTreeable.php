<?php

namespace Blast\BaseEntitiesBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;

trait NestedTreeable {
    
    /**
     * @var integer
     */
    private $treeLft;

    /**
     * @var integer
     */
    private $treeRgt;

    /**
     * @var integer
     */
    private $treeLvl;

    /**
     * @var \Entity\Category
     */
    private $treeRoot;

    /**
     * @var \Entity\Category
     */
    private $treeParent;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $treeChildren;
    
    /**
     * Set treeLft
     *
     * @param integer $treeLft
     * @return mixed $this
     */
    public function setTreeLft($treeLft)
    {
        $this->treeLft = $treeLft;

        return $this;
    }

    /**
     * Get treeLft
     *
     * @return integer 
     */
    public function getTreeLft()
    {
        return $this->treeLft;
    }

    /**
     * Set treeRgt
     *
     * @param integer $treeRgt
     * @return mixed $this
     */
    public function setTreeRgt($treeRgt)
    {
        $this->treeRgt = $treeRgt;

        return $this;
    }

    /**
     * Get treeRgt
     *
     * @return integer 
     */
    public function getTreeRgt()
    {
        return $this->treeRgt;
    }

    /**
     * Set treeLvl
     *
     * @param integer $treeLvl
     * @return mixed $this
     */
    public function setTreeLvl($treeLvl)
    {
        $this->treeLvl = $treeLvl;

        return $this;
    }

    /**
     * Get treeLvl
     *
     * @return integer 
     */
    public function getTreeLvl()
    {
        return $this->treeLvl;
    }

    /**
     * Add treeTreeChildren
     *
     * @param Object $treeChild
     * @return mixed $this
     */
    public function addTreeChild($treeChild)
    {
        $this->treeChildren[] = $treeChild;

        return $this;
    }

    /**
     * Remove treeChild
     *
     * @param Object $treeChild
     */
    public function removeTreeChild($treeChild)
    {
        $this->treeChildren->removeElement($treeChild);
    }

    /**
     * Get treeChildren
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTreeChildren()
    {
        return $this->treeChildren;
    }

    /**
     * Set treeRoot
     *
     * @param Object $treeRoot
     * @return mixed $this
     */
    public function setTreeRoot($treeRoot = null)
    {
        $this->treeRoot = $treeRoot;

        return $this;
    }

    /**
     * Get treeRoot
     *
     * @return Object
     */
    public function getTreeRoot()
    {
        return $this->treeRoot;
    }

    /**
     * Set treeParent
     *
     * @param Object $treeParent
     * @return mixed
     */
    public function setTreeParent($treeParent = null)
    {
        $this->treeParent = $treeParent;

        return $this;
    }

    /**
     * Get treeParent
     *
     * @return object
     */
    public function getTreeParent()
    {
        return $this->treeParent;
    }
    
    public function initCollections()
    {
        $this->treeChildren = new ArrayCollection();
    }
}
