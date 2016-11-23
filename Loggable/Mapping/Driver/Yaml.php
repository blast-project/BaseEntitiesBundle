<?php

namespace Librinfo\BaseEntitiesBundle\Loggable\Mapping\Driver;

use Gedmo\Mapping\Driver\File;
use Gedmo\Mapping\Driver;
use Gedmo\Exception\InvalidMappingException;
use Librinfo\CoreBundle\Tools\Reflection\ClassAnalyzer;

/**
 * This is a yaml mapping driver for Loggable
 * behavioral extension. Used for extraction of extended
 * metadata from yaml specifically for Loggable
 * extension.
 *
 * It changes the behaviour of the original Loggable extension:
 * - the prefix used is "librinfo" instead of "gedmo"
 * - all fields are versioned by default unless they are tagged unversioned
 * - marks fields from traits as versioned
 *
 * @author Boussekeyt Jules <jules.boussekeyt@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 * @author Libre Informatique [http://www.libre-informatique.fr]
 * @license GNU GPL v3 (http://www.gnu.org/licenses/)
 */
class Yaml extends File implements Driver
{
    /**
     * File extension
     * @var string
     */
    protected $_extension = '.dcm.yml';

    /**
     * {@inheritDoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {
        $mapping = $this->_getMapping($meta->name);

        // Entities that have the Loggable trait don't need the librinfo:loggable entry in the yaml file
        if ( ClassAnalyzer::hasTrait($meta->name, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Loggable') )
        {
            $config['loggable'] = true;
        }

        elseif (isset($mapping['librinfo'])) {
            $classMapping = $mapping['librinfo'];
            if (isset($classMapping['loggable'])) {
                $config['loggable'] = true;
                if (isset ($classMapping['loggable']['logEntryClass'])) {
                    if (!$cl = $this->getRelatedClassName($meta, $classMapping['loggable']['logEntryClass'])) {
                        throw new InvalidMappingException("LogEntry class: {$classMapping['loggable']['logEntryClass']} does not exist.");
                    }
                    $config['logEntryClass'] = $cl;
                }
            }
        }

        if ( !isset($config['loggable']) )
            return;

        if ( isset($mapping['fields']) )
            foreach ( $mapping['fields'] as $field => $fieldMapping )
                if ( !isset($fieldMapping['librinfo']) || !in_array('unversioned', $fieldMapping['librinfo']) )
                {
                    // fields cannot be overrided and throws mapping exception
                    if ( $meta->isCollectionValuedAssociation($field) )
                        throw new InvalidMappingException("Cannot versioned [{$field}] as it is collection in object - {$meta->name}");
                    $config['versioned'][] = $field;
                }

        if (isset($mapping['attributeOverride']))
            foreach ($mapping['attributeOverride'] as $field => $fieldMapping)
                if ( !isset($fieldMapping['librinfo']) || !in_array('unversioned', $fieldMapping['librinfo']) )
                {
                    // fields cannot be overrided and throws mapping exception
                    if ($meta->isCollectionValuedAssociation($field))
                        throw new InvalidMappingException("Cannot versioned [{$field}] as it is collection in object - {$meta->name}");
                    $config['versioned'][] = $field;
                }

        if (isset($mapping['manyToOne']))
            foreach ($mapping['manyToOne'] as $field => $fieldMapping)
                if ( !isset($fieldMapping['librinfo']) || !in_array('unversioned', $fieldMapping['librinfo']) )
                {
                    // fields cannot be overrided and throws mapping exception
                    if ($meta->isCollectionValuedAssociation($field))
                        throw new InvalidMappingException("Cannot versioned [{$field}] as it is collection in object - {$meta->name}");
                    $config['versioned'][] = $field;
                }

        if (isset($mapping['oneToOne']))
            foreach ($mapping['oneToOne'] as $field => $fieldMapping)
                if ( !isset($fieldMapping['librinfo']) || !in_array('unversioned', $fieldMapping['librinfo']) )
                {
                     // fields cannot be overrided and throws mapping exception
                    if ($meta->isCollectionValuedAssociation($field))
                        throw new InvalidMappingException("Cannot versioned [{$field}] as it is collection in object - {$meta->name}");
                    $config['versioned'][] = $field;
                }

        if (!$meta->isMappedSuperclass && $config) {
            if ( is_array($meta->identifier) && count($meta->identifier) > 1 )
            {
                throw new InvalidMappingException("Loggable does not support composite identifiers in class - {$meta->name}");
            }
            if ( isset($config['versioned']) && !isset($config['loggable']) )
            {
                throw new InvalidMappingException("Class must be annoted with Loggable annotation in order to track versioned fields in class - {$meta->name}");
            }
            if ( !empty($config['loggable']) )
            {
                $this->addTraitsMetadata($meta, $config);
            }
        }
    }

    public function addTraitsMetadata($meta, array &$config)
    {
        // add versioned fields for entities that have the Nameable trait
        if ( ClassAnalyzer::hasTrait($meta->name, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Nameable' ) )
        {
            $config['versioned'][] = 'name';
        }
        // add versioned fields for entities that have the Descriptible trait
        if ( ClassAnalyzer::hasTrait($meta->name, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Descriptible' ) )
        {
            $config['versioned'][] = 'description';
        }
        // add versioned fields for entities that have the Emailable trait
        if ( ClassAnalyzer::hasTrait($meta->name, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Emailable' ) )
        {
            $config['versioned'][] = 'email';
            $config['versioned'][] = 'emailNpai';
            $config['versioned'][] = 'emailNoNewsletter';
        }
        // add versioned fields for entities that have the Addressable trait
        if ( ClassAnalyzer::hasTrait($meta->name, 'Librinfo\BaseEntitiesBundle\Entity\Traits\Addressable' ) )
        {
            $config['versioned'][] = 'address';
            $config['versioned'][] = 'zip';
            $config['versioned'][] = 'city';
            $config['versioned'][] = 'country';
            $config['versioned'][] = 'npai';
            $config['versioned'][] = 'vcardUid';
            $config['versioned'][] = 'confirmed';
        }
    }



    /**
     * {@inheritDoc}
     */
    protected function _loadMappingFile($file)
    {
        return \Symfony\Component\Yaml\Yaml::parse(file_get_contents($file));
    }
}
