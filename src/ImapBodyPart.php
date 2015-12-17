<?php


namespace Humps\MailManager;


use Humps\MailManager\Contracts\BodyPart;

class ImapBodyPart implements BodyPart
{
    private $bodyType;
    private $encoding;
    private $subtype;
    private $section;
    private $charset;
    private $embedded;
    private $id;
    private $name;

    function __construct($bodyType, $encoding, $subtype, $section, $charset = null, $name = null, $embedded = false, $id = null)
    {
        $this->bodyType = $bodyType;
        $this->encoding = $encoding;
        $this->subtype = $subtype;
        $this->section = $section;
        $this->charset = $charset;
        $this->embedded = $embedded;
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return boolean
     */
    public function isEmbedded()
    {
        return $this->embedded;
    }

    /**
     * @param boolean $embedded
     */
    public function setEmbedded($embedded)
    {
        $this->embedded = $embedded;
    }

    /**
     * @return null
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param null $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * @return mixed
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * @param mixed $bodyType
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;
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

    /**
     * @return mixed
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * @param mixed $subtype
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

}