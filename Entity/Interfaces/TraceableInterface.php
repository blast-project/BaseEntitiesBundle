<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Interfaces;

use DateTime;
use FOS\UserBundle\Model\UserInterface;

interface TraceableInterface
{

    public function getCreatedDate();

    public function setCreatedDate(DateTime $createdDate);

    public function getLastUpdateDate();

    public function setLastUpdateDate(DateTime $lastUpdateDate);

    public function getCreatedBy();

    public function setCreatedBy(UserInterface $createdBy);

    public function getUpdatedBy();

    public function setUpdatedBy(UserInterface $updatedBy);
}