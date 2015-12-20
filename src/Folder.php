<?php


namespace Humps\MailManager;


use Humps\MailManager\Collections\Collectable;

class Folder implements Collectable
{
    private $folder;

    public function __construct($folder)
    {
        $this->folder = (array)$folder;
    }

    public function getMailboxName()
    {
        return $this->folder['name'];
    }

    public function getName()
    {
        return explode("}", $this->folder['name'])[1];
    }

    public function getAttributes()
    {
        return $this->folder['attributes'];
    }

    public function getDelimiter()
    {
        return $this->folder['delimiter'];
    }

    public function getDetails()
    {
        return $this->folder;
    }

}