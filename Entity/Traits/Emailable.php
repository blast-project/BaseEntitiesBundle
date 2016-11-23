<?php

namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait Emailable
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
     * @return Emailable
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
     * @return Emailable
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
     * @return Emailable
     */
    public function setEmailNoNewsletter($emailNoNewsletter)
    {
        $this->emailNoNewsletter = $emailNoNewsletter;
        return $this;
    }
}
