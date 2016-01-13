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

    public function getSearchIndexClass()
    {
        return $this->getClassName() . 'SearchIndex';
    }

    public function getSearchIndexTable()
    {
        return $this->getEntityManager()->getClassMetadata($this->getSearchIndexClass())->getTableName();
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
                ->from($this->getSearchIndexClass(), "i$k")
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

    /**
     * Does a batch update of the whole search index table
     */
    public function batchUpdate()
    {
        if (! $this->truncateTable() )
            throw new \Exception('Could not truncate table ' . $this->getSearchIndexTable());

        $em = $this->getEntityManager();
        $indexClass = $this->getSearchIndexClass();

        $batchSize = 100;
        $offset = 0;
        $query = $this->createQueryBuilder('o')
            ->setMaxResults($batchSize);

        do {
            $query->setFirstResult($offset);
            $entities = $query->getQuery()->execute();
            foreach ( $entities as $entity )
            {
                $fields = $indexClass::$fields;
                foreach ( $fields as $field )
                {
                    $keywords = $entity->analyseField($field);
                    foreach ( $keywords as $keyword )
                    {
                        $index = new $indexClass;
                        $index->setObject($entity);
                        $index->setField($field);
                        $index->setKeyword($keyword);
                        $em->persist($index);
                    }
                }
            }
            $em->flush();
            $offset += $batchSize;
        }
        while ( count($entities) > 0 );
    }

    /**
     * Truncates the search index table
     * @return boolean true if success
     */
    public function truncateTable()
    {
        $connection = $this->getEntityManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $q = $dbPlatform->getTruncateTableSql($this->getSearchIndexTable());
            $connection->executeUpdate($q);
            $connection->commit();
            return true;
        }
        catch (\Exception $e) {
            $connection->rollback();
            return false;
        }
    }
}
