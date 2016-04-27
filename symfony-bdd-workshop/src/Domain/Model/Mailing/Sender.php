<?php

namespace Domain\Model\Mailing;

class Sender
{
    /**
     * @var EmailAddress
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @param EmailAddress $emailAddress
     */
    public function __construct(EmailAddress $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getEmailAddress()->__toString();
    }

    /**
     * @return EmailAddress
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }
}
