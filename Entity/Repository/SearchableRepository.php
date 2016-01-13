<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Librinfo\BaseEntitiesBundle\SearchAnalyser\SearchAnalyser;

use Doctrine\ORM\EntityRepository;

class SearchableRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        $this->_entityName = $class->name;
        $this->_em         = $em;
        $this->_class      = $class;
    }

    /**
     * Find the entities that have the appropriate keywords in their searchIndex
     *
     * @param string $searchText
     * @param int $maxResults
     * @return Collection found entities
     */
    public function indexSearch($searchText, $maxResults)
    {
        // split the phrase into words
        $words = SearchAnalyser::analyse($searchText);
        if ( !$words )
            return [];

        $query = $this->createQueryBuilder('o')
            ->setMaxResults($maxResults);

        $parameters = [];
        foreach ( $words as $k => $word )
        {
            $subquery = $query->getEntityManager()->createQueryBuilder()
                ->select("i$k.keyword")
                ->from($this->getClassName() . 'SearchIndex', "i$k")
                ->where("i$k.object = o")
                ->andWhere("i$k.keyword LIKE :search$k");
            $query->andWhere($query->expr()->exists( $subquery->getDql()));
            $parameters["search$k"] = $word . '%';
        }
        $query->setParameters($parameters);
        //dump($query->getQuery()->getSQL());
        $results = $query->getQuery()->execute();
        return $results;
    }
}
