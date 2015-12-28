<?php


namespace Humps\MailManager;


use Humps\MailManager\Collections\Collectable;
use JsonSerializable;

class Attachment implements Collectable, JsonSerializable
{

    protected $filename;
    protected $part;
    protected $encoding;
    protected $attachment;

    function __construct($filename, $part, $encoding, array $attachment = [])
    {
        $this->filename = $filename;
        $this->part = $part;
        $this->encoding = $encoding;
        $this->attachment = $attachment;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * @param mixed $part
     */
    public function setPart($part)
    {
        $this->part = $part;
    }

    /**
     * @return mixed
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param mixed $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    public function setAttachment(array $attachment)
    {
        $this->attachment = $attachment;
    }

    public function getAttachment()
    {
        return $this->attachment;
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
        return [
            'filename' => $this->filename,
            'part'     => $this->part,
            'encoding' => $this->encoding
        ];
    }
}