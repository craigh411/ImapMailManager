<?php

namespace Humps\MailManager;

use Carbon\Carbon;
use Humps\MailManager\Contracts\Message;

class ImapMessage implements Message
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
            return $this->message['fromaddress'];
        }

        $from = $this->getEmails($this->message['from']);
        return $from;
    }

    public function getCC()
    {
        $cc = null;
        if (isset($this->message['cc'])) {
            $cc = $this->message['cc'];
            $cc = $this->getEmails($cc);
        }

        return $cc;
    }

    public function getTo()
    {
        $to = $this->message['to'];
        $to = $this->getEmails($to);

        return $to;
    }

    public function setHtmlBody($body)
    {
        // Convert any non-utf messages to utf-8
        if (mb_detect_encoding($body) !== "UTF-8") {
            $body = utf8_encode($body);
        }
        // This makes non-printable chars less obtrusive in chrome
        $body = mb_convert_encoding($body, "UTF-8");

        $this->message['html_body'] = $body;
    }

    public function getTextBody()
    {
        return (isset($this->message['text_body'])) ? $this->message['text_body'] : null;
    }

    public function getHtmlBody()
    {

        return (isset($this->message['html_body'])) ? $this->message['html_body'] : null;
    }


    public function setTextBody($body)
    {
        $this->message['text_body'] = $body;
    }

    public function setInlineAttachments()
    {

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
     * @param $emails
     * @return array
     */
    protected function getEmails($emails)
    {
        $emailAddresses = [];

        foreach ($emails as $key => $email) {
            $mailbox = $this->decode($email->mailbox);
            $host = $this->decode($email->host);
            $emailAddresses[] = new EmailAddress($mailbox, $host);
        }

        return $emailAddresses;
    }

    public function __toString()
    {
        return $this->getTextBody();
    }
}