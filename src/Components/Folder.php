<?php


namespace Humps\MailManager\Components;


use Humps\MailManager\Collections\Contracts\Collectable;
use JsonSerializable;
use stdClass;

class Folder implements Collectable, JsonSerializable
{
    protected $folder;
    protected $name;
    protected $mailboxName;
    protected $attributes;
    protected $delimiter;

    function __construct($name, $mailboxName, $attributes, $delimiter, $folder)
    {
        $this->name = $name;
        $this->mailboxName = $mailboxName;
        $this->attributes = $attributes;
        $this->delimiter = $delimiter;
        $this->folder = $folder;
    }

    /**
     * Returns the returned folder details as an associative array
     * @return array
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Sets the returned folder details as an associative array
     * @param array $folder
     */
    public function setFolder(array $folder)
    {
        $this->folder = $folder;
    }

    /**
     * Returns the name of the folder
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the folder
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the entire mailbox name
     * @return string
     */
    public function getMailboxName()
    {
        return $this->mailboxName;
    }

    /**
     * Sets the entire mailbox name
     * @param string $mailboxName
     */
    public function setMailboxName($mailboxName)
    {
        $this->mailboxName = $mailboxName;
    }

    /**
     * Returns the attributes for the folder. A bitmask that can be tested against.
     * @link http://php.net/manual/en/function.imap-getmailboxes.php
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the attributes for the folder.
     * @link http://php.net/manual/en/function.imap-getmailboxes.php
     * @param mixed $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return mixed
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * @param mixed $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Factory method for creating the Folder object from the returned imap_getmailboxes() response.
     * @param array|object $folder
     */
    public static function create($folder)
    {
        if ($folder instanceof stdClass) {
            $folder = (array)$folder;
        }

        $mailboxName = (isset($folder['name'])) ? $folder['name'] : null;
        $name = (isset($folder['name'])) ? explode("}", $folder['name'])[1] : null;
        $attributes = (isset($folder['attributes'])) ? $folder['attributes'] : null;
        $delimiter = (isset($folder['name'])) ? $folder['delimiter'] : null;

        return new static($name, $mailboxName, $attributes, $delimiter, $folder);
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return [
            'mailboxName' => $this->mailboxName,
            'name'        => $this->name,
            'attributes'  => $this->attributes,
            'delimiter'   => $this->delimiter
        ];
    }
}