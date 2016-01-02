<?php

namespace Humps\MailManager;

use Carbon\Carbon;

use Humps\MailManager\Collections\AttachmentCollection;
use Humps\MailManager\Collections\Collectable;
use Humps\MailManager\Collections\EmailCollection;
use Humps\MailManager\Collections\Jsonable;
use Humps\MailManager\Contracts\Message;
use JsonSerializable;

class ImapMessage implements Collectable, JsonSerializable, Jsonable, Message
{
    protected $message;
    protected $messageNum;
    protected $uid;
    protected $subject;
    protected $from;
    protected $to;
    protected $cc;
    protected $bcc;
    protected $htmlBody;
    protected $textBody;
    protected $attachments;
    protected $size;
    protected $date;
    protected $important;
    protected $read;
    protected $answered;


    /**
     * Returns all the message details as an array
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the message details
     * @param array $message
     */
    public function setMessage(array $message)
    {
        $this->message = $message;
    }

    /**
     * Returns the message number
     * @return int
     */
    public function getMessageNum()
    {
        return $this->messageNum;
    }

    /**
     * Sets the message number
     * @param int $messageNum
     */
    public function setMessageNum($messageNum)
    {
        $this->messageNum = trim($messageNum);
    }

    /**
     * Returns the unique message id
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Sets the unique message id
     * @param string $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Returns the subject
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets the subject
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Returns a collection of EmailsAddress objects for the from field
     * @return EmailCollection
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets the collection of Email objects for the from field
     * @param EmailCollection $from
     */
    public function setFrom(EmailCollection $from)
    {
        $this->from = $from;
    }

    /**
     * Returns a collection of EmailsAddress objects for the to field
     * @return EmailCollection
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets the collection of Email objects for the to field
     * @param EmailCollection $from
     */
    public function setTo(EmailCollection $to)
    {
        $this->to = $to;
    }

    /**
     * Returns a collection of EmailsAddress objects for the cc field
     * @return EmailCollection
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Sets the collection of Email objects for the cc field
     * @param EmailCollection $from
     */
    public function setCc(EmailCollection $cc)
    {
        $this->cc = $cc;
    }

    /**
     * Returns a collection of EmailsAddress objects for the bcc field
     * @return EmailCollection
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * Sets the collection of Email objects for the bcc field
     * @param EmailCollection $from
     */
    public function setBcc(EmailCollection $bcc)
    {
        $this->bcc = $bcc;
    }

    /**
     * Returns the html body for the message
     * @return string
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * Sets the html body for the message
     * @param string $htmlBody
     */
    public function setHtmlBody($htmlBody)
    {
        $this->htmlBody = $htmlBody;
    }

    /**
     * Returns the text/plain body for the message
     * @return string
     */
    public function getTextBody()
    {
        return $this->textBody;
    }

    /**
     * Sets the text/plain body for the message
     * @param string $textBody
     */
    public function setTextBody($textBody)
    {
        $this->textBody = $textBody;
    }

    /**
     * Returns a collection of Attachment objects
     * @return AttachmentCollection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Sets the attachments for the message
     * @param AttachmentCollection $attachments
     */
    public function setAttachments(AttachmentCollection $attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * Whether or not the Message has attachments
     * @return int
     */
    public function hasAttachments()
    {
        return (count($this->attachments)) ? true : false;
    }

    /**
     * The number of attachments
     * @return int
     */
    public function attachmentCount()
    {
        return count($this->attachments);
    }


    /**
     * Returns the size of the message
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Sets the size of the message
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Returns a Carbon date
     * @return Carbon
     */
    public function getDate()
    {
        return Carbon::parse($this->date);
    }

    /**
     * Sets the date
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Returns the raw date as set with setDate
     * @return string
     */
    public function getRawDate()
    {
        return $this->date;
    }

    /**
     * Returns the important flag
     * @return bool
     */
    public function isImportant()
    {
        return $this->important;
    }

    /**
     * Sets the important flag
     * @param bool $important
     */
    public function setImportant($important)
    {
        $this->important = $important;
    }

    /**
     * Returns the seen/read flag
     * @return bool
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * Sets the seen/read flag
     * @param bool $read
     */
    public function setRead($read)
    {
        $this->read = $read;
    }

    /**
     * Returns the answer flag
     * @return bool
     */
    public function isAnswered()
    {
        return $this->answered;
    }

    /**
     * Sets the answered flag
     * @param bool $answered
     */
    public function setAnswered($answered)
    {
        $this->answered = $answered;
    }


    /**
     * Returns a string representation of this object. In this case JSON.
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Make deep copy when cloned
     */
    public function __clone()
    {
        $this->to = clone $this->to;
        $this->from = clone $this->from;
        $this->cc = clone $this->cc;
        $this->bcc = clone $this->bcc;
        $this->attachments = clone $this->attachments;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'messageNum'  => $this->messageNum,
            'uid'         => $this->uid,
            'subject'     => $this->subject,
            'date'        => $this->getRawDate(),
            'to'          => $this->to,
            'from'        => $this->from,
            'cc'          => $this->cc,
            'bcc'         => $this->bcc,
            'textBody'   => $this->textBody,
            'htmlBody'   => $this->htmlBody,
            'size'        => $this->size,
            'attachments' => $this->attachments,
            'important'   => $this->important,
            'read'        => $this->read,
            'answered'    => $this->answered,
        ];
    }

    /*
     * Returns the json representation of this object
     */
    public function toJson()
    {
        return json_encode($this);
    }
}