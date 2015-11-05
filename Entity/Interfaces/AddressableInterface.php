<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Interfaces;

interface AddressableInterface
{
    public function getAddressName();

    public function setAddressName($addressName);

    public function getAddressDescription();

    public function setAddressDescription($addressDescription);

    public function getAddress();

    public function setAddress($address);

    public function getPostalCode();

    public function setPostalCode($postalCode);

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

    public function getVcardUid();

    public function setVcardUid($vcardUid);

    public function isAddressConfirmed();

    public function setAddressConfirmed($addressConfirmed);
}