<?php

namespace Librinfo\BaseEntitiesBundle\Form\Type;

class TreeableChoiceType extends TreeableType
{
    public function getParent()
    {
        return 'librinfo_baseentities_treeable';
    }
}
