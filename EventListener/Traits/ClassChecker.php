<?php

namespace Librinfo\BaseEntitiesBundle\EventListener\Traits;

use Librinfo\CoreBundle\Tools\Reflection\ClassAnalyzer;

trait ClassChecker
{
    /**
     * @var ClassAnalyzer
     */
    private $classAnalyzer;

    public function setClassAnalyser($classAnalyzer)
    {
        $this->classAnalyzer = new $classAnalyzer;
        return $this;
    }

    public function hasTrait($object, $traitFQDN)
    {
        return $this->classAnalyzer->hasTrait($object, $traitFQDN);
    }
}
