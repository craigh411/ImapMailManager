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
        return $this->decode($this->message['subject']);
    }

    public function getFrom($asString = true)
    {
        if ($asString) {
            return $this->decode($this->message['fromaddress']);
        }

        $from = $this->decode($this->message['from']);
        $from = $this->addEmailAttributes($from);

        return $from;
    }

    public function getCC($asString = false)
    {
        $cc = null;
        if (isset($this->message['cc'])) {
            if ($asString) {
                return $this->decode($this->message['ccaddress']);
            }

            $cc = $this->message['cc'];
            $cc = $this->addEmailAttributes($cc);
        }

        return $cc;
    }

    public function getTo($asString = false)
    {
        if ($asString) {
            return $this->decode($this->message['toaddress']);
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
        return $this->decode($this->message['MailDate']);
    }

    public function getHeaderDate()
    {
        return $this->decode($this->message['date']);
    }

    public function setAttachments($attachments)
    {
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

    private function decode($header)
    {
        return utf8_encode(imap_mime_header_decode($header)[0]->text);
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
            $to[$key]['mailbox'] = $this->decode($email['mailbox']);
            $to[$key]['host'] = $this->decode($email['host']);
            $to[$key]['email'] = $email['mailbox'] . '@' . $email['host'];
        }
        return $to;
    }
}