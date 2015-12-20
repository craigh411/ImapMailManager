<?php

namespace Humps\MailManager;

use Carbon\Carbon;

use Humps\MailManager\Collections\Collectable;
use Humps\MailManager\Collections\EmailCollection;
use Humps\MailManager\Contracts\Message;

class ImapMessage implements Message, Collectable
{

    protected $message;
    protected $messageNo;
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


    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage(array $message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessageNo()
    {
        return $this->messageNo;
    }

    /**
     * @param mixed $messageNo
     */
    public function setMessageNo($messageNo)
    {
        $this->messageNo = $messageNo;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom(EmailCollection $from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo(EmailCollection $to)
    {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param mixed $cc
     */
    public function setCc(EmailCollection $cc)
    {
        $this->cc = $cc;
    }

    /**
     * @return mixed
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param mixed $bcc
     */
    public function setBcc(EmailCollection $bcc)
    {
        $this->bcc = $bcc;
    }

    /**
     * @return mixed
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @param mixed $htmlBody
     */
    public function setHtmlBody($htmlBody)
    {
        $this->htmlBody = $htmlBody;
    }

    /**
     * @return mixed
     */
    public function getTextBody()
    {
        return $this->textBody;
    }

    /**
     * @param mixed $textBody
     */
    public function setTextBody($textBody)
    {
        $this->textBody = $textBody;
    }

    /**
     * @return mixed
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param mixed $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return Carbon::parse($this->date);
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getRawDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $rawDate
     */
    public function setRawDate($rawDate)
    {
        $this->rawDate = $rawDate;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTextBody();
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
    }

}