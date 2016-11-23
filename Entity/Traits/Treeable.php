<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use Librinfo\BaseEntitiesBundle\Entity\Traits\Tree\Node;
use Librinfo\BaseEntitiesBundle\Entity\Traits\Tree\NodeInterface;

trait Treeable
{
    use Node;

    private $sortMaterializedPath;

    public function setChildNodeOf(NodeInterface $node)
    {
        $separator = static::getMaterializedPathSeparator();
        $path = rtrim($node->getRealMaterializedPath(), $separator);
        $this->setMaterializedPath($path);

        // TODO: getSortField
        $sortPath = rtrim($node->getSortMaterializedPath(), $separator);
        $sortString = method_exists($this, 'getSortField') ? $this->getSortField() : (string)$this;
        $this->setSortMaterializedPath($sortPath . $separator . $sortString . $separator . $this->getId() );

        if (null !== $this->parentNode)
        {
            $this->parentNode->getChildNodes()->removeElement($this);
        }

        $this->parentNode = $node;
        $this->parentNode->addChildNode($this);

        foreach ($this->getChildNodes() as $child)
        {
            $child->setChildNodeOf($this);
        }

        return $this;
    }

    public function setParentNode(NodeInterface $node = null)
    {
        if ($node !== null)
        {
            $this->parentNode = $node;
            $this->setChildNodeOf($this->parentNode);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortMaterializedPath()
    {
        return $this->sortMaterializedPath;
    }

    /**
     * @param mixed $sortMaterializedPath
     *
     * @return Treeable
     */
    public function setSortMaterializedPath($sortMaterializedPath)
    {
        $this->sortMaterializedPath = $sortMaterializedPath;
        return $this;
    }


    public function getParentNodeId()
    {
        $path = explode(static::getMaterializedPathSeparator(), $this->getRealMaterializedPath());
        if (count($path) < 2)
            return null;

        array_pop($path);
        $parent_id = array_pop($path);
        return $parent_id;
    }
}