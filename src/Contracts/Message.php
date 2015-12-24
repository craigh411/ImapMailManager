<?php

namespace Humps\MailManager\Contracts;

use Carbon\Carbon;
use Humps\MailManager\Collections\AttachmentCollection;
use Humps\MailManager\Collections\EmailCollection;

interface Message
{
    /**
     * Returns all the message details as an array
     * @return array
     */
    public function getMessage();

    /**
     * Sets the message details
     * @param array $message
     */
    public function setMessage(array $message);

    /**
     * Returns the message number
     * @return int
     */
    public function getMessageNo();

    /**
     * Sets the message number
     * @param int $messageNo
     */
    public function setMessageNo($messageNo);

    /**
     * Returns the unique message id
     * @return int
     */
    public function getUid();

    /**
     * Sets the unique message id
     * @param int $messageNo
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
     * @param EmailCollection $from
     */
    public function setTo(EmailCollection $to);

    /**
     * Returns a collection of EmailsAddress objects for the cc field
     * @return EmailCollection
     */
    public function getCc();

    /**
     * Sets the collection of Email objects for the cc field
     * @param EmailCollection $from
     */
    public function setCc(EmailCollection $cc);

    /**
     * Returns a collection of EmailsAddress objects for the bcc field
     * @return EmailCollection
     */
    public function getBcc();

    /**
     * Sets the collection of Email objects for the bcc field
     * @param EmailCollection $from
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
     * @return AttachmentCollection
     */
    public function getAttachments();

    /**
     * Sets the attachments for the message
     * @param AttachmentCollection $attachments
     */
    public function setAttachments(AttachmentCollection $attachments);

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

}