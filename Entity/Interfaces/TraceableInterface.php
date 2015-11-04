<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Interfaces;

use DateTime;

interface TraceableInterface
{

    public function getCreatedDate();

    public function setCreatedDate(DateTime $createdDate);

    public function getLastUpdateDate();

    public function setLastUpdateDate(DateTime $lastUpdateDate);
}