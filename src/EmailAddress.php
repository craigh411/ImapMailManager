<?php


namespace Humps\MailManager;


class EmailAddress
{

    protected $mailbox;
    protected $host;

    public function __construct($mailbox = null, $host = null)
    {
        $this->mailbox = $mailbox;
        $this->host = $host;
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
     * How to convert object to string
     * @return string
     */
    public function __toString()
    {
        return $this->getEmailAddress();
    }

}