<?php


namespace Humps\MailManager;


class Mailbox
{
    private $server;
    private $username;
    private $password;
    private $port;
    private $folder;
    private $ssl;
    private $validateCert;


    public function __construct($server, $username, $password, $port = null, $folder = 'INBOX', $ssl = true, $validateCert = true)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
        $this->folder = $folder;
        $this->ssl = $ssl;
        $this->validateCert = $validateCert;
    }

    /**
     * @return boolean
     */
    public function isSsl()
    {
        return $this->ssl;
    }

    /**
     * @param boolean $ssl
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
    }

    /**
     * @return boolean
     */
    public function isValidateCert()
    {
        return $this->validateCert;
    }

    /**
     * @param boolean $validateCert
     */
    public function setValidateCert($validateCert)
    {
        $this->validateCert = $validateCert;
    }


    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param mixed $folder
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    }

}