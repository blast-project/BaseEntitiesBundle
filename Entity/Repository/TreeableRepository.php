<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class TreeableRepository extends EntityRepository implements TreeableRepositoryInterface
{
    use Tree\Tree;

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

    public function createOrderedQB($min_node_level = 0, $max_node_level = 0)
    {
        $qb = $this->createQueryBuilder('t')->orderBy('t.sortMaterializedPath', 'ASC');
        if ( $min_node_level > 1)
            $qb->andWhere('t.materializedPath LIKE :path')->setParameter ('path', str_repeat ('/%', $min_node_level - 1));
        if ( $max_node_level )
            $qb->andWhere('t.materializedPath NOT LIKE :path')->setParameter ('path', str_repeat ('/%', $max_node_level));
        return $qb;
    }

}