<?php

namespace Librinfo\BaseEntitiesBundle\Entity;

abstract class SearchIndexEntity
{
    /**
     * @var string
     */
    protected $keyword;

    /**
     * @var string
     */
    private $field;


    /**
     * @var string
     */
    private $object_id;
}