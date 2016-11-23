<?php

namespace Librinfo\BaseEntitiesBundle\Doctrine\ORM\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;

/**
 * Adds searchable functionnality to the Doctrine Yaml metadata driver
 * @todo : This is not used (yet)
 */
class SearchableYamlDriver extends SimplifiedYamlDriver
{
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        parent::loadMetadataForClass($className, $metadata);

        // TODO : force use of SearchableClassMetadata instead of ClassMetadata
    }
}
