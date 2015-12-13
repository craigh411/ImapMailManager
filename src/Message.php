<?php

namespace Humps\ImapMailManager;

use Carbon\Carbon;

class Message
{

    private $message;

    public function __construct($message)
    {
        $this->message = (array)$message;
    }

    public function getMessageNo()
    {

        return trim($this->message['Msgno']);
    }


    public function getSubject()
    {
        return $this->message['subject'];
    }

    public function getFrom($asString = true)
    {
        if ($asString) {
            return $this->message['fromaddress'];
        }

        $from = $this->message['from'];
        $from = $this->addEmailAttributes($from);

        return $from;
    }

    public function getCC($asString = false)
    {
        $cc = null;
        if (isset($this->message['cc'])) {
            if ($asString) {
                return $this->message['ccaddress'];
            }

            $cc = $this->message['cc'];
            $cc = $this->addEmailAttributes($cc);
        }

        return $cc;
    }

    public function getTo($asString = false)
    {
        if ($asString) {
            return $this->message['toaddress'];
        }

        $to = $this->message['to'];
        $to = $this->addEmailAttributes($to);

        return $to;
    }

    public function getBody()
    {
        return trim($this->message['body']);
    }

    public function setBody($body)
    {
        $this->message['body'] = $body;
    }

    public function getSize()
    {
        return $this->message['Size'];
    }

    /**
     * Returns the Carbon parsed date
     * @return Carbon
     */
    public function getDate()
    {
        return Carbon::parse($this->message['MailDate']);
    }

    public function getRawDate()
    {
        return $this->message['MailDate'];
    }

    public function getHeaderDate()
    {
        return $this->message['date'];
    }

    public function setAttachments($attachments)
    {

        $attachments = array();


        $this->message['attachments'] = $attachments;
    }

    public function getAttachments()
    {
        return $this->message['attachments'];
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param $to
     * @param $email
     * @return array
     */
    private function addEmailAttributes($to)
    {
        foreach ($to as $key => &$email) {
            $to[$key] = (array)$email;
            $to[$key]['email'] = $email['mailbox'] . '@' . $email['host'];
        }

        return $to;
    }
}