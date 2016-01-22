<?php

namespace Humps\MailManager\Contracts;


/**
 * Decodes an Email messages.
 * Class Decoder
 * @package Humps\MailManager]
 */
interface Decoder
{
    /**
     * Decodes the message body. If a valid encoding is not passed then it will attempt to detect the encoding itself.
     * @param $body
     * @param null $encoding
     * @return string
     */
    public function decodeBody($body, $encoding = null);

    /**
     * Decodes the given email header
     * @param $header
     * @return string
     */
    public function decodeHeader($header);
}