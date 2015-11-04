<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Interfaces;

interface AdressableInterface
{
    public function getName();

    public function setName($name);

    public function getAddress();

    public function setAddress($address);

    public function getPostalcode();

    public function setPostalcode($postalcode);

    public function getCity();

    public function setCity($city);

    public function getCountry();

    public function setCountry($country);

    public function isNpai();

    public function setNpai($npai);

    public function getEmail();

    public function setEmail($email);

    public function isEmailNpai();

    public function setEmailNpai($emailNpai);

    public function isEmailNoNewsletter();

    public function setEmailNoNewsletter($emailNoNewsletter);

    public function getDescription();

    public function setDescription($description);

    public function getVcardUid();

    public function setVcardUid($vcardUid);

    public function isConfirmed();

    public function setConfirmed($confirmed);
}