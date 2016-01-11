<?php

namespace Librinfo\BaseEntitiesBundle\Entity;

abstract class SearchIndexEntity
{
    public static $fields = [];

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
     * @var object Entity
     */
    protected $object;

    /**
     * @param string $field
     * @return self
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $keyword
     * @return self
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param object $object
     * @return self
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }
}