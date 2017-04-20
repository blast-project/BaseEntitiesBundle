<?php

namespace Blast\BaseEntitiesBundle\Admin\Traits;

Trait NestedTreeableAdmin
{   
    public function createQuery($context = 'list')
    {
        $proxyQuery = parent::createQuery($context);
        // Default Alias is "o"
        $proxyQuery->addOrderBy('o.treeRoot', 'ASC');
        $proxyQuery->addOrderBy('o.treeLft', 'ASC');

        return $proxyQuery;
    }
}

