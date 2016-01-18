<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\Logger;
use Psr\Log\LoggerAwareInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class NamingListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata'
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        $this->logger->debug("[NamingListener] Entering NamingListener for « loadClassMetadata » event");

        $namingStrategy = $eventArgs
                ->getEntityManager()
                ->getConfiguration()
                ->getNamingStrategy();

        // create a FQDN for the representing table
        if ( $namingStrategy->classToTableName($metadata->getName()) == $metadata->table['name'] )
            $metadata->table['name'] = $this->buildTableName($metadata->name);

        // create a FQDN for the ManyToMany induced tables
        foreach ( $metadata->associationMappings as $field => $mapping )
        if ( $mapping['type'] == ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide'] )
        if ( $namingStrategy->classToTableName($mapping['joinTable']['name']) == $mapping['joinTable']['name'] )
        {
            $rc = new \ReflectionClass($mapping['targetEntity']);
            $fqdn = $mapping['sourceEntity'].'__'.$rc->getShortName();
            $metadata->associationMappings[$field]['joinTable']['name'] = $this->buildTableName($fqdn);
        }

        $this->logger->debug(
            "[NamingListener] Added table naming strategy to Entity",
            ['class' => $metadata->getName()]
        );
    }

    protected static function buildTableName($class)
    {
        $tableName = str_replace('Bundle\\Entity', '', $class);
        $tableName = str_replace('\\', '_', $tableName);
        $tableName = strtolower($tableName);
        return $tableName;
    }
}
