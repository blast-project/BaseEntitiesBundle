<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

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
     * @return PlantCategory
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
     * @return PlantCategory
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
     * @return PlantCategory
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
     * @param \Librinfo\VarietiesBundle\Entity\PlantCategory $treeChild
     * @return PlantCategory
     */
    public function addTreeChild(\Librinfo\VarietiesBundle\Entity\PlantCategory $treeChild)
    {
        $this->treeChildren[] = $treeChild;

        return $this;
    }

    /**
     * Remove treeChild
     *
     * @param \Librinfo\VarietiesBundle\Entity\PlantCategory $treeChild
     */
    public function removeTreeChild(\Librinfo\VarietiesBundle\Entity\PlantCategory $treeChild)
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
     * @param \Librinfo\VarietiesBundle\Entity\PlantCategory $treeRoot
     * @return PlantCategory
     */
    public function setTreeRoot(\Librinfo\VarietiesBundle\Entity\PlantCategory $treeRoot = null)
    {
        $this->treeRoot = $treeRoot;

        return $this;
    }

    /**
     * Get treeRoot
     *
     * @return \Librinfo\VarietiesBundle\Entity\PlantCategory 
     */
    public function getTreeRoot()
    {
        return $this->treeRoot;
    }

    /**
     * Set treeParent
     *
     * @param \Librinfo\VarietiesBundle\Entity\PlantCategory $treeParent
     * @return PlantCategory
     */
    public function setTreeParent(\Librinfo\VarietiesBundle\Entity\PlantCategory $treeParent = null)
    {
        $this->treeParent = $treeParent;

        return $this;
    }

    /**
     * Get treeParent
     *
     * @return \Librinfo\VarietiesBundle\Entity\PlantCategory 
     */
    public function getTreeParent()
    {
        return $this->treeParent;
    }
}
