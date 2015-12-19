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
            if (isset($this->message['fromaddress'])) {
                $from = preg_replace(['/^\"/', '/\"\s+? </'], '', $this->message['fromaddress']);
                return $this->decode($from);
            }

            return "unknown";
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
        if (isset($this->message['to'])) {
            $to = $this->message['to'];
            return $this->getEmails($to);
        }

        return [];
    }

    public function setHtmlBody($body)
    {
        $this->message['html_body'] = $body;
    }

    public function getTextBody()
    {
        return (isset($this->message['text_body'])) ? $this->message['text_body'] : "";
    }

    public function getHtmlBody()
    {

        return (isset($this->message['html_body'])) ? $this->message['html_body'] : "";
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


        $header = imap_mime_header_decode($header);
        $str = '';

        $encodings = mb_list_encodings();
        // Add simplified Chinese (GB2312), supported but not listed and widely used.
        $encodings[] = 'GB2312';

        // Make all strings upper case for comparison, charsets are presented
        // with different case and we are using in_array() for comparison
        $encodings = array_map(function ($encoding) {
            return strtoupper($encoding);
        }, $encodings);


        // A bit hacky, but imap_mime_header_decode() doesn't map the '£' symbol from ISO-8859-1,
        // so ISO-8859-1 strings need to be run through utf8_encode() for correct display.
        if (count($header)) {
            foreach ($header as $h) {
                if (strtoupper($h->charset) === "ISO-8859-1" || !in_array($h->charset, $encodings)) {
                    $str .= utf8_encode($h->text);
                } else {
                    $str .= mb_convert_encoding($h->text, 'utf-8', $h->charset);
                }
            }
        }
        return $str;
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

    public function isUnread()
    {
        return $this->message['Unseen'];
    }

    public function __toString()
    {
        return $this->getTextBody();
    }
}