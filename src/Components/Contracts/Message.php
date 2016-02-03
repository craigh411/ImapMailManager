<?php

namespace Humps\MailManager\Components\Contracts;

use Carbon\Carbon;
use Humps\MailManager\Collections\ImapAttachmentCollection;
use Humps\MailManager\Collections\EmailCollection;

interface Message
{


    /**
     * Returns the message number
     * @return int
     */
    public function getMessageNum();

    /**
     * Sets the message number
     * @param int $messageNo
     */
    public function setMessageNum($messageNo);

    /**
     * Returns the unique message id
     * @return int
     */
    public function getUid();

    /**
     * Sets the unique message id
     * @param int $uid
     */
    public function setUid($uid);

    /**
     * Returns the subject
     * @return string
     */
    public function getSubject();

    /**
     * Sets the subject
     * @param string $subject
     */
    public function setSubject($subject);

    /**
     * Returns a collection of EmailsAddress objects for the from field
     * @return EmailCollection
     */
    public function getFrom();

    /**
     * Sets the collection of Email objects for the from field
     * @param EmailCollection $from
     */
    public function setFrom(EmailCollection $from);

    /**
     * Returns a collection of EmailsAddress objects for the to field
     * @return EmailCollection
     */
    public function getTo();

    /**
     * Sets the collection of Email objects for the to field
     * @param EmailCollection $to
     */
    public function setTo(EmailCollection $to);

    /**
     * Returns a collection of EmailsAddress objects for the cc field
     * @return EmailCollection
     */
    public function getCc();

    /**
     * Sets the collection of Email objects for the cc field
     * @param EmailCollection $cc
     */
    public function setCc(EmailCollection $cc);

    /**
     * Returns a collection of EmailsAddress objects for the bcc field
     * @return EmailCollection
     */
    public function getBcc();

    /**
     * Sets the collection of Email objects for the bcc field
     * @param EmailCollection $bcc
     */
    public function setBcc(EmailCollection $bcc);

    /**
     * Returns the html body for the message
     * @return string
     */
    public function getHtmlBody();

    /**
     * Sets the html body for the message
     * @param string $htmlBody
     */
    public function setHtmlBody($htmlBody);

    /**
     * Returns the text/plain body for the message
     * @return string
     */
    public function getTextBody();

    /**
     * Sets the text/plain body for the message
     * @param string $textBody
     */
    public function setTextBody($textBody);

    /**
     * Returns a collection of Attachment objects
     * @return ImapAttachmentCollection
     */
    public function getAttachments();

    /**
     * Sets the attachments for the message
     * @param ImapAttachmentCollection $attachments
     */
    public function setAttachments(ImapAttachmentCollection $attachments);

    /**
     * Returns the size of the message
     * @return int
     */
    public function getSize();

    /**
     * Sets the size of the message
     * @param int $size
     */
    public function setSize($size);

    /**
     * Returns a Carbon date
     * @return Carbon
     */
    public function getDate();

    /**
     * Sets the date
     * @param string $date
     */
    public function setDate($date);

    /**
     * Returns the raw date as set with setDate
     * @return string
     */
    public function getRawDate();

    /**
     * Returns the important flag
     * @return bool
     */
    public function isImportant();

    /**
     * Sets the important flag
     * @param bool $important
     */
    public function setImportant($important);

    /**
     * Returns the seen/read flag
     * @return bool
     */
    public function isRead();

    /**
     * Sets the seen/read flag
     * @param bool $read
     */
    public function setRead($read);

    /**
     * Returns the answer flag
     * @return bool
     */
    public function isAnswered();

    /**
     * Sets the answered flag
     * @param bool $answered
     */
    public function setAnswered($answered);

    /**
     * Sets the Message structure
     * @param array $structure
     */
    public function setStructure($structure);

    /**
     * Returns the Message structure
     * @return array $structure
     */
    public function getStructure();

    /**
     * Sets the Message headers
     * @param array $headers
     */
    public function setHeaders($headers);

    /**
     * Returns the Message headers
     * @return array $headers
     */
    public function getHeaders();

    /**
     * Sets the Message body parts
     * @param array $bodyParts
     */
    public function setBodyParts(array $bodyParts);

    /**
     * Returns the Message body parts
     * @return array $bodyParts
     */
    public function getBodyParts();
}