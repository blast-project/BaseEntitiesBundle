<?php

namespace Blast\BaseEntitiesBundle\Entity\Traits;

use Blast\BaseEntitiesBundle\Entity\Traits\Nameable;

trait Addressable
{
    use Nameable;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $zip;

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
    private $vcardUid;

    /**
     * @var boolean
     */
    private $confirmed = true;

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
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     *
     * @return Addressable
     */
    public function setZip($zip)
    {
       $this->zip = $zip;
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
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param boolean $confirmed
     *
     * @return Addressable
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
        return $this;
    }

    /**
     * @param string $separator
     * @return string
     */
    public function getFulltextAddress($separator = "\n")
    {
        $elems = [];
        if ($this->address)
            $elems[] = $this->address;
        $zip_city = [];
        if ($this->zip)
            $zip_city[] = $this->zip;
        if ($this->city)
            $zip_city[] = $this->city;

        if ($zip_city)
            $elems[] = implode(' ', $zip_city);
        if ($this->country)
            $elems[] = $this->country;
        return implode($separator, $elems);
    }
}
