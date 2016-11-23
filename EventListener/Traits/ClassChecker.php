<?php

namespace Librinfo\BaseEntitiesBundle\EventListener\Traits;

use Librinfo\CoreBundle\Tools\Reflection\ClassAnalyzer;

trait ClassChecker
{
    /**
     * @var ClassAnalyzer
     */
    private $classAnalyzer;

    /**
     * @param string $classAnalyzer     class analyzer FQCN
     * @return this
     */
    public function setClassAnalyser($classAnalyzer)
    {
        $this->classAnalyzer = new $classAnalyzer;
        return $this;
    }


    public function hasTrait($object, $traitFQDN)
    {
        return $this->classAnalyzer->hasTrait($object, $traitFQDN);
    }

    /**
     * Returns all parents of a class (parent, parent of parent, parent of parent's parent and so on)
     *
     * @param ReflectionClass|string   $class   A ReflectionClass object or a class name (FQCN)
     * @return array
     */
    public function getAncestors($class)
    {
        return $this->classAnalyzer->getAncestors($class);
    }
}
