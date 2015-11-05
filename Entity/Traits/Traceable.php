<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

use DateTime;
use FOS\UserBundle\Model\UserInterface;

trait Traceable
{
    /**
     * @var DateTime
     */
    private $createdDate = null;

    /**
     * @var UserInterface
     */
    private $createdBy = null;

    /**
     * @var DateTime
     */
    private $lastUpdateDate = null;

    /**
     * @var UserInterface
     */
    private $updatedBy = null;

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

    /**
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param UserInterface $createdBy
     *
     * @return Traceable
     */
    public function setCreatedBy(UserInterface $createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param UserInterface $updatedBy
     *
     * @return Traceable
     */
    public function setUpdatedBy(UserInterface $updatedBy)
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }



}