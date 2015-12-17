<?php

namespace Humps\MailManager\Contracts;

interface BodyPart
{
    /**
     * @return mixed
     */
    public function getBodyType();

    /**
     * @param mixed $bodyType
     */
    public function setBodyType($bodyType);

    /**
     * @return mixed
     */
    public function getEncoding();

    /**
     * @param mixed $encoding
     */
    public function setEncoding($encoding);

    /**
     * @return mixed
     */
    public function getSubtype();

    /**
     * @param mixed $subtype
     */
    public function setSubtype($subtype);

    /**
     * @return mixed
     */
    public function getSection();

    /**
     * @param mixed $section
     */
    public function setSection($section);
}