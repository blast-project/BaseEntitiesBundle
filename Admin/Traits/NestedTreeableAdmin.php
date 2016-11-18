<?php

namespace Librinfo\BaseEntitiesBundle\Admin\Traits;

Trait NestedTreeableAdmin
{   
    public function createQuery($context = 'list')
    {
        $proxyQuery = parent::createQuery($context);
        // Default Alias is "o"
        $proxyQuery->addOrderBy('o.treeRoot', 'DESC');
        $proxyQuery->addOrderBy('o.treeLft', 'ASC');

        return $proxyQuery;
    }
}

