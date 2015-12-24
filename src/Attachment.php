<?php


namespace Humps\MailManager;


use Humps\MailManager\Collections\Collectable;
use JsonSerializable;

class Attachment implements Collectable, JsonSerializable
{
    function __construct()
    {

    }

    public function getFileName()
    {

    }

    public function isEmbedded()
    {

    }

    public function getEncoding()
    {

    }

    public function getContent()
    {

    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}