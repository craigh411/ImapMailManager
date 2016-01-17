<?php

namespace Humps\MailManager\Components\Contracts;

interface BodyPart
{
    /**
     * Returns the name of the body part (usually the filename)
     * @return string|null
     */
    public function getName();

    /**
     * Sets the name of the body part (usually the filename)
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * Returns the id of the body part
     * @return string
     */
    public function getId();

    /**
     * Sets the id of the body part
     * @param string $id
     * @return void
     */
    public function setId($id);

    /**
     * Returns the charset for the body part
     * @return string
     */
    public function getCharset();

    /**
     * Sets the charset for the body part
     * @param string $charset
     * @return void
     */
    public function setCharset($charset);

    /**
     * Returns the type of the body part
     * @return string
     */
    public function getBodyType();

    /**
     * Sets the type of the body part
     * @param string $bodyType
     * @return void
     */
    public function setBodyType($bodyType);

    /**
     * Returns the encoding of the body part
     * @return string
     */
    public function getEncoding();

    /**
     * Sets the encoding of the body part
     * @param string $encoding
     * @return void
     */
    public function setEncoding($encoding);

    /**
     * Gets the sub type of the body part
     * @return string
     */
    public function getSubtype();

    /**
     * Sets the sub type of the body part
     * @param string $subtype
     * @return void
     */
    public function setSubtype($subtype);

    /**
     * Gets the section of the body part
     * @return string
     */
    public function getSection();

    /**
     * Sets the section of the body part
     * @param string $section
     * @param void
     */
    public function setSection($section);
}