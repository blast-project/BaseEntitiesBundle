<?php

namespace Librinfo\BaseEntitiesBundle\Entity;

abstract class SearchIndexEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $keyword;

    /**
     * @var string
     */
    protected $field;


    /**
     * @var string
     */
    protected $object;
}