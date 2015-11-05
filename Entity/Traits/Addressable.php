<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait Addressable
{
    /**
     * @var string
     */
    private $addressName;

    /**
     * @var string
     */
    private $addressDescription;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $country;

    /**
     * @var boolean
     */
    private $npai = false;

    /**
     * @var string
     */
    private $email;

    /**
     * @var boolean
     */
    private $emailNpai = false;

    /**
     * @var boolean
     */
    private $emailNoNewsletter = false;

    /**
     * @var string
     */
    private $vcardUid;

    /**
     * @var boolean
     */
    private $addressConfirmed = true;

    /**
     * @return string
     */
    public function getAddressName()
    {
        return $this->addressName;
    }

    /**
     * @param string $addressName
     *
     * @return Addressable
     */
    public function setAddressName($addressName)
    {
        $this->addressName = $addressName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return Addressable
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     *
     * @return Addressable
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return Addressable
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return Addressable
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isNpai()
    {
        return $this->npai;
    }

    /**
     * @param boolean $npai
     *
     * @return Addressable
     */
    public function setNpai($npai)
    {
        $this->npai = $npai;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Addressable
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEmailNpai()
    {
        return $this->emailNpai;
    }

    /**
     * @param boolean $emailNpai
     *
     * @return Addressable
     */
    public function setEmailNpai($emailNpai)
    {
        $this->emailNpai = $emailNpai;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEmailNoNewsletter()
    {
        return $this->emailNoNewsletter;
    }

    /**
     * @param boolean $emailNoNewsletter
     *
     * @return Addressable
     */
    public function setEmailNoNewsletter($emailNoNewsletter)
    {
        $this->emailNoNewsletter = $emailNoNewsletter;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressDescription()
    {
        return $this->addressDescription;
    }

    /**
     * @param string $addressDescription
     *
     * @return Addressable
     */
    public function setAddressDescription($addressDescription)
    {
        $this->addressDescription = $addressDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getVcardUid()
    {
        return $this->vcardUid;
    }

    /**
     * @param string $vcardUid
     *
     * @return Addressable
     */
    public function setVcardUid($vcardUid)
    {
        $this->vcardUid = $vcardUid;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAddressConfirmed()
    {
        return $this->addressConfirmed;
    }

    /**
     * @param boolean $addressConfirmed
     *
     * @return Addressable
     */
    public function setAddressConfirmed($addressConfirmed)
    {
        $this->addressConfirmed = $addressConfirmed;
        return $this;
    }
}