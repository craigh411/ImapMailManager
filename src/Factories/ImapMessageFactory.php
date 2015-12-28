<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\Collections\EmailCollection;
use Humps\MailManager\EmailAddress;
use Humps\MailManager\EmailDecoder;
use Humps\MailManager\ImapMessage;

class ImapMessageFactory
{

    protected static $headers;

    /**
     * Creates an ImapMessage object from the headers returned from `imap_headerinfo()`.
     * @param $headers
     * @return ImapMessage
     */
    public static function create($headers)
    {
        static::$headers = (array)$headers;

        $m = new ImapMessage();
        $m->setMessage(static::$headers);
        $m->setMessageNo(static::getAttr('Msgno'));
        $m->setUid(static::getAttr('message_id'));
        $m->setSubject(static::getAttr('subject'));
        $m->setFrom(static::getEmails(static::getAttr('from', false)));
        $m->setCc(static::getEmails(static::getAttr('cc', false)));
        $m->setBcc(static::getEmails(static::getAttr('bcc', false)));
        $m->setTo(static::getEmails(static::getAttr('to', false)));
        $m->setSize(static::getAttr('size'));
        $m->setDate(static::getAttr('MailDate'));
        $m->setRead(!static::getAttr('Unseen'));
        $m->setImportant(static::getAttr('Flagged'));
        $m->setAnswered(static::getAttr('Answered'));

        return $m;
    }

    /**
     * Returns an EmailCollection from the Email headers
     * @param $emails
     * @return EmailCollection
     */
    protected static function getEmails($emails)
    {
        $emailCollection = new EmailCollection();
        if ($emails) {
            foreach ($emails as $key => $email) {
                $mailbox = EmailDecoder::decodeHeader($email->mailbox);
                $host = EmailDecoder::decodeHeader($email->host);
                $personal = (isset($email->personal)) ? EmailDecoder::decodeHeader($email->personal) : null;
                $emailCollection->add(new EmailAddress($mailbox, $host, $personal));
            }
        }
        return $emailCollection;
    }


    /**
     * Returns the given attribute from the message array
     * @param $attribute
     * @return null
     */
    protected static function getAttr($attribute, $decode = true)
    {
        if ($decode) {
            return (isset(static::$headers[$attribute])) ? EmailDecoder::decodeHeader(static::$headers[$attribute]) : null;
        }

        return (isset(static::$headers[$attribute])) ? static::$headers[$attribute] : null;
    }
}