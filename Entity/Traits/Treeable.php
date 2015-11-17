<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use Knp\DoctrineBehaviors\Model\Tree\Node;
use Knp\DoctrineBehaviors\Model\Tree\NodeInterface;

trait Treeable
{
    use Node;

    private $sortMaterializedPath;

    public function setChildNodeOf(NodeInterface $node)
    {
        $path = rtrim($node->getRealMaterializedPath(), static::getMaterializedPathSeparator());
        $this->setMaterializedPath($path);

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

//    public function isRootNode()
//    {
//        return self::getMaterializedPathSeparator().self::getId() === $this->getParentMaterializedPath();
//    }
//
//    public function getNodeLevel()
//    {
//        return count($this->getExplodedPath()) - 1;
//    }
//
//    public function getRootMaterializedPath()
//    {
//        $explodedPath = $this->getExplodedPath();
//
//        return static::getMaterializedPathSeparator() . array_shift($explodedPath);
//    }
//
//    public function getParentMaterializedPath()
//    {
//        $path = $this->getExplodedPath();
//
//        array_pop($path);
//
//        $parentPath = static::getMaterializedPathSeparator().implode(static::getMaterializedPathSeparator(), $path);
//
//        return $parentPath;
//    }
//
//    protected function getExplodedPath()
//    {
//        $path = explode(static::getMaterializedPathSeparator(), $this->getRealMaterializedPath());
//
//        return array_filter($path, function($item) {
//            return static::getMaterializedPathSeparator().$this->getId() !== $item;
//        });
//    }
}