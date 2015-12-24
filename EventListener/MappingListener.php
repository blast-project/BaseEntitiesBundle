<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Id\UuidGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Librinfo\BaseEntitiesBundle\EventListener\Traits\ClassChecker;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class MappingListener implements EventSubscriber
{
    use ClassChecker;

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

        // Do not generate id mapping twice for entities that extend a MappedSuperclass
        if ($metadata->isMappedSuperclass)
            return;

        $reflectionClass = $metadata->getReflectionClass();

        if ( !$reflectionClass || !$this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Idable') )
        {
            return;
        } // return if current entity doesn't use Idable trait

        // return if the current entity doesn't use Idable trait
        if (!$reflectionClass || !$this->hasTrait($reflectionClass, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Idable'))
            return;

        $metadata->mapField([
            'id' => true,
            'fieldName' => "id",
            'type' => "guid",
            'columnName' => "id",
        ]);
        $metadata->setIdGenerator(new UuidGenerator());
    }

    protected static function buildTableName($class)
    {
        $tableName = str_replace('Bundle\\Entity', '', $class);
        $tableName = str_replace('\\', '_', $tableName);
        $tableName = strtolower($tableName);
        return $tableName;
    }
}
