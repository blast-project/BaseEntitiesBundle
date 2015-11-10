<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use DateTime;
//use Librinfo\UserBundle\Entity\Traits\Traceable;

trait Traceable
{
    use \Librinfo\UserBundle\Entity\Traits\Traceable;
    
    /**
     * @var DateTime
     */
    private $createdAt = null;

    /**
     * @var DateTime
     */
    private $updatedAt = null;

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return Traceable
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $lastUpdatedAt
     *
     * @return Traceable
     */
    public function setLastUpdatedAt(DateTime $lastUpdatedAt)
    {
        $this->updatedAt = $lastUpdatedAt;
        return $this;
    }
}
