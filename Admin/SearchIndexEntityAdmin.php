<?php

namespace Blast\BaseEntitiesBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;

class SearchIndexEntityAdmin extends CoreAdmin
{
    public static function searchIndexCallback($admin, $property, $value){
        $searchIndex = $admin->getClass() . 'SearchIndex';
        $datagrid = $admin->getDatagrid();
        $queryBuilder = $datagrid->getQuery();
        $alias = $queryBuilder->getRootalias();

        $queryBuilder
            ->leftJoin($searchIndex, 's', 'WITH', $alias . '.id = s.object')
            ->where('s.keyword LIKE :value')
            ->setParameter('value', "%$value%")
        ;

       // $datagrid->setValue($property, null, $value);
  }
}
