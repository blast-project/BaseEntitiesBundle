<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use DateTime;

trait Traceable
{
    /**
     * @var DateTime
     */
    private $createdDate;

    /**
     * @var DateTime
     */
    private $lastUpdateDate;

    /**
     * @return DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param DateTime $createdDate
     *
     * @return Traceable
     */
    public function setCreatedDate(DateTime $createdDate)
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }

    /**
     * @param DateTime $lastUpdateDate
     *
     * @return Traceable
     */
    public function setLastUpdateDate(DateTime $lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;
        return $this;
    }

}