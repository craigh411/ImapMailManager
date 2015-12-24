<?php


namespace Humps\MailManager;


use Humps\MailManager\Collections\Collectable;
use JsonSerializable;

class EmailAddress implements Collectable, JsonSerializable
{

    protected $mailbox;
    protected $host;
    protected $personal;

    public function __construct($mailbox = null, $host = null, $personal = null)
    {
        $this->mailbox = $mailbox;
        $this->host = $host;
        $this->personal = $personal;
    }


    /**
     * @return mixed
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     * @param mixed $mailbox
     */
    public function setMailbox($mailbox)
    {
        $this->mailbox = $mailbox;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Returns the Email address as a string by calling __toString()
     * @return $this
     */
    public function getEmailAddress()
    {
        return $this->mailbox . '@' . $this->host;
    }


    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Returns the personal information for the E-mail (i.e. real name or company name)
     * @return string
     */
    public function getPersonal()
    {
        return $this->personal;
    }

    /**
     * Sets the personal information for the E-mail (i.e. real name or company name)
     * @param string $personal
     */
    public function setPersonal($personal)
    {
        $this->personal = $personal;
    }

    /**
     * How to convert object to string
     * @return string
     */
    public function __toString()
    {
        return $this->getEmailAddress();
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'personal' => $this->personal,
            'email'    => $this->getEmailAddress(),
            'mailbox'  => $this->mailbox,
            'host'     => $this->getHost()
        ];
    }
}