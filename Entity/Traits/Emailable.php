<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait Addressable
{
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
}
