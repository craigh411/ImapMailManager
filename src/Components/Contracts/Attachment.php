<?php

namespace Humps\MailManager\Components\Contracts;

interface Attachment
{
    /**
     * Returns the name of the file
     * @return string
     */
    public function getFilename();

    /**
     * Sets the name of the file
     * @param string $filename
     */
    public function setFilename($filename);

    /**
     * Returns the part number of the attachment
     * @return string
     */
    public function getPart();

    /**
     * Sets the part number of the attachment
     * @param string $part
     */
    public function setPart($part);

    /**
     * Returns the encoding.
     * for constants
     * @return int
     */
    public function getEncoding();

    /**
     * Sets the encoding for the attachment
     * @param int $encoding
     */
    public function setEncoding($encoding);

    /**
     * Sets the attachment array.
     * @param array $attachment
     */
    public function setAttachment(array $attachment);

    /**
     * Returns all the attachment details returned from the server as an array.
     * @return array
     */
    public function getAttachment();

    /**
     * Factory method for creating a new attachment object
     * @param array|object $attachment
     * @return static
     */
    public static function create($attachment);

}