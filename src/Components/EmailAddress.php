<?php


namespace Humps\MailManager\Components;


use Humps\MailManager\Collections\Contracts\Collectable;
use JsonSerializable;
use stdClass;

class EmailAddress implements Collectable, JsonSerializable
{

    protected $mailbox;
    protected $host;
    protected $personal;
    protected $email;

    public function __construct($mailbox, $host, $personal, $email)
    {
        $this->mailbox = $mailbox;
        $this->host = $host;
        $this->personal = $personal;
        $this->email = $email;
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
     * Returns the email array
     * @return array
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email array
     * @param array $email
     */
    public function setEmail(array $email)
    {
        $this->email = $email;
    }


    /**
     * Factory method for creating an EmailAddress object
     * @param $email
     * @return static
     */
    public static function create($email)
    {
        if ($email instanceof stdClass) {
            $email = (array)$email;
        }

        $mailbox = (isset($email['mailbox'])) ? $email['mailbox'] : null;
        $host = (isset($email['host'])) ? $email['host'] : null;
        $personal = (isset($email['personal'])) ? $email['personal'] : null;

        return new static($mailbox, $host, $personal, $email);
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
            'emailAddress'    => $this->getEmailAddress(),
            'mailbox'  => $this->mailbox,
            'host'     => $this->getHost()
        ];
    }
}