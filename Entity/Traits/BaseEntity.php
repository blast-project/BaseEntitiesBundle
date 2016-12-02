<?php

namespace Blast\BaseEntitiesBundle\Entity\Traits;

use Blast\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\BaseEntitiesBundle\Entity\Traits\Stringable;

trait BaseEntity
{
    use Guidable;
    use Stringable;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $links;
}
