<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait Adressable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $postalcode;

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
    private $description;

    /**
     * @var string
     */
    private $vcardUid;

    /**
     * @var boolean
     */
    private $confirmed = true;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Adressable
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return Adressable
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * @param string $postalcode
     *
     * @return Adressable
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
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
     * @return Adressable
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
     * @return Adressable
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
     * @return Adressable
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
     * @return Adressable
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
     * @return Adressable
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
     * @return Adressable
     */
    public function setEmailNoNewsletter($emailNoNewsletter)
    {
        $this->emailNoNewsletter = $emailNoNewsletter;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Adressable
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return Adressable
     */
    public function setVcardUid($vcardUid)
    {
        $this->vcardUid = $vcardUid;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param boolean $confirmed
     *
     * @return Adressable
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
        return $this;
    }
}