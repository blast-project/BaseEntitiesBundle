<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use Knp\DoctrineBehaviors\Model\Tree\Node;
use Knp\DoctrineBehaviors\Model\Tree\NodeInterface;

trait Treeable
{
    use Node;

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
}