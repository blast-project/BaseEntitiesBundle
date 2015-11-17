<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Knp\DoctrineBehaviors\ORM as ORMBehaviors;

class TreeableRepository extends EntityRepository implements TreeableRepositoryInterface
{
    use ORMBehaviors\Tree\Tree;

    /**
     * {@inheritdoc}
     */
    public function getRootNodesWithTree()
    {
//        $all = $this->findAll();
        $all = $this->findBy([],['sortMaterializedPath'=>'ASC']);
        $allRootNodes = array();
        foreach ($all as $node)
        {
            if ($node->isRootNode())
            {
                $allRootNodes[] = $node;
            }
        }

        foreach ($allRootNodes as $root)
        {
            $root->buildTree($all);
        }

        return $allRootNodes;
    }
}