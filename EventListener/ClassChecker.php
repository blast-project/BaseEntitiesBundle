<?php

namespace Librinfo\BaseEntitiesBundle\EventListener;

use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;

trait ClassChecker
{
    /**
     * @var ClassAnalyzer
     */
    private $classAnalyzer;

    public function setClassAnalyser($classAnalyzer)
    {
        $this->classAnalyzer = new $classAnalyzer;
    }

    public function hasTrait($object, $traitFQDN)
    {
        if (!$object instanceof \ReflectionClass && is_object($object))
            $object = new \ReflectionClass($object);
        return $this->classAnalyzer->hasTrait($object, $traitFQDN, true);
    }
}