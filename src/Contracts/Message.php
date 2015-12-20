<?php

namespace Humps\MailManager\Contracts;

interface Message
{
    /**
     * Returns the message number
     * @return mixed
     */
    public function getMessageNo();

    /**
     * Returns the subject of the E-mail
     * @return mixed
     */
    public function getSubject();

    /**
     * Return the from addresses or an array of from addresses if $asString is false
     * @param bool|true $asString
     * @return mixed
     */
    public function getFrom();

    /**
     * Return an array of CC addresses
     * @return array
     */
    public function getCC();

    /**
     * Returns an array of to addresses
     * @return array
     */
    public function getTo();


    /**
     * Sets the body of the message
     * @param $body
     * @return mixed
     */
    public function setTextBody($body);

    /**
     * Returns the body of the message
     * @return mixed
     */
    public function getTextBody();

    /**
     * Returns the body of the message
     * @return mixed
     */
    public function getHtmlBody();

    /**
     * Sets the body of the message
     * @param $body
     * @return mixed
     */
    public function setHtmlBody($body);


    /**
     * returns the size of the message
     * @return float
     */
    public function getSize();

    /**
     * A formatted version of the date
     * @return mixed
     */
    public function getDate();

    /**
     * The raw date as returned from the server
     * @return mixed
     */
    public function getRawDate();


    /**
     * Sets the attachments
     * @param $attachments
     */
    public function setAttachments($attachments);

    /**
     * Returns an array of attachments
     * @return array
     */
    public function getAttachments();

    /**
     * Get the entire message
     * @return mixed
     */
    public function getMessage();
}