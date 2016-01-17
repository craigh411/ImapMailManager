<?php


namespace Humps\MailManager\Components;


use Humps\MailManager\Components\Contracts\BodyPart;

class ImapBodyPart implements BodyPart
{
    protected $bodyType;
    protected $encoding;
    protected $subtype;
    protected $section;
    protected $charset;
    protected $id;
    protected $name;

    /**
     * ImapBodyPart constructor.
     * @param string $bodyType
     * @param string $encoding
     * @param string $subtype
     * @param string $section
     * @param string $charset
     * @param string $name
     * @param string $id
     */
    function __construct($bodyType, $encoding, $subtype, $section, $charset = null, $name = null, $id = null)
    {
        $this->bodyType = $bodyType;
        $this->encoding = $encoding;
        $this->subtype = $subtype;
        $this->section = $section;
        $this->charset = $charset;
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Returns the name of the body part (usually the filename)
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the body part (usually the filename)
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the id of the body part
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the body part
     * @param string $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the charset for the body part
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Sets the charset for the body part
     * @param string $charset
     * @return void
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Returns the type of the body part
     * @return string
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * Sets the type of the body part
     * @param string $bodyType
     * @return void
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;
    }

    /**
     * Returns the encoding of the body part
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Sets the encoding of the body part
     * @param string $encoding
     * @return void
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * Gets the sub type of the body part
     * @return string
     */
    public function getSubtype()
    {
        return $this->subtype;
    }

    /**
     * Sets the sub type of the body part
     * @param string $subtype
     * @return void
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
    }

    /**
     * Gets the section of the body part
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Sets the section of the body part
     * @param string $section
     * @param void
     */
    public function setSection($section)
    {
        $this->section = $section;
    }
}