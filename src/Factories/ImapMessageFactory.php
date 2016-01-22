<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\Components\ImapAttachment;
use Humps\MailManager\Collections\ImapAttachmentCollection;
use Humps\MailManager\Collections\EmailCollection;
use Humps\MailManager\Contracts\Decoder;
use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\Components\EmailAddress;
use Humps\MailManager\MessageDecoder;
use Humps\MailManager\Components\ImapBodyPart;
use Humps\MailManager\Components\ImapMessage;

class ImapMessageFactory
{

    protected $headers;
    protected $bodyParts;
    protected $structure;
    protected $imap;
    protected $messageNum;
    protected $outputEncoding;

    public static function create($messageNum, Imap $imap, $excludeBody = false, $peek = false, Decoder $decoder = null, $outputEncoding = "UTF-8")
    {
        $factory = new static($messageNum, $imap, $peek, $excludeBody, $decoder, $outputEncoding);
        return $factory->getMessage();
    }

    /**
     * Construct this object for creating an ImapMessage
     * ImapMessageFactory constructor.
     * @param $messageNum
     * @param $imap
     */
    protected function __construct($messageNum, $imap, $peek, $excludeBody, Decoder $decoder = null, $outputEncoding)
    {
        $this->imap = $imap;
        $this->messageNum = $messageNum;
        $this->decoder = (!$decoder) ? new MessageDecoder() : $decoder;
        $this->outputEncoding = $outputEncoding;

        $this->structure = $imap->fetchStructure($messageNum);

        $this->headers = (array)$imap->getMessageHeaders($messageNum);


        $this->message = new ImapMessage();
        $this->message->setStructure($this->structure);
        $this->message->setHeaders($this->headers);

        $this->bodyParts = $this->flattenBodyParts($this->structure);
        $this->message->setBodyParts($this->bodyParts);

        if (!$excludeBody) {
            $options = ($peek) ? FT_PEEK : 0;
            $this->setMessageBody($options);
        }

        $this->setMessageHeaders();
    }

    /**
     * Returns the message
     * @return \Humps\MailManager\Components\ImapMessage
     */
    protected function getMessage()
    {
        return $this->message;
    }

    /**
     * Returns an array of BodyParts. The array is broken down into sections, so all parts from section 1
     * will be at index 0 ($bodyParts[0]), part 2 at index 1 ($bodyParts[1]) etc.
     *
     * @param array $structure The structure retrieved from getStructure();
     * @param array $parts The array being returned.
     * @param array $sections The current section number as array [1,1,1] is '1.1.1'
     * @return array An array of <a href="Contracts/BodyPart.html">BodyPart</a> objects
     */
    protected function flattenBodyParts($structure, array $parts = [], array $sections = [])
    {
        // Append 1 to the sections array as we are in the next section on each call.
        $sections[] = 1;
        if (isset($structure->parts) && count($structure->parts)) {
            foreach ($structure->parts as $i => $part) {
                if (isset($part->parts)) {
                    // We have more parts, lets get them
                    $parts = $this->flattenBodyParts($part, $parts, $sections);
                } else {
                    // Make the last element of array the loop number, that's the final section we are in!
                    // e.g. 1.1.2
                    $sections[count($sections) - 1] = $i + 1;
                    // Break array in to the main sections
                    $section = implode(".", $sections);
                    $bodyPart = new ImapBodyPart($part->type, $part->encoding, $part->subtype, $section);
                    $this->setParams($part, $bodyPart);
                    $this->setDispositionParams($part, $bodyPart);
                    if (isset($part->id)) {
                        $bodyPart->setId($part->id);
                    }

                    $parts[$sections[0] - 1][] = $bodyPart;
                }
            }
        } elseif (isset($structure) && count($structure)) {
            // We only have 1 part, so add all the sections.
            $parts[0][] = new ImapBodyPart($structure->type, $structure->encoding, $structure->subtype, 1);
        }

        return $parts;
    }

    /**
     * Sets returned name and charset params on the BodyPart object such if they exist.
     * @param $part
     * @param $bodyPart
     * @return mixed
     */
    private function setParams($part, &$bodyPart)
    {
        // Check for charset and embedded files (these will be in parameters)
        if ($part->ifparameters) {
            foreach ($part->parameters as $param) {
                if ($param->attribute == 'CHARSET') {
                    $bodyPart->setCharset($param->value);
                }
                if ($param->attribute == 'NAME') {
                    $bodyPart->setName($param->value);
                }
            }
        }
    }

    /**
     * Sets the disposition params (essentially the filename of the attachments which haven't been embedded)
     * @param $part
     * @param $bodyPart
     * @return void
     */
    private function setDispositionParams($part, &$bodyPart)
    {
        // Now lets look for disposition parameters for attachments
        if ($part->ifdparameters) {
            if ($part->disposition === "ATTACHMENT") {
                foreach ($part->dparameters as $param) {
                    if ($param->attribute == 'FILENAME') {
                        $bodyPart->setName($param->value);
                    }
                }
            }
        }
    }

    /**
     * Creates an ImapMessage object from the headers returned from `imap_headerinfo()`.
     * @param $headers
     * @return ImapMessage
     */
    protected function setMessageHeaders()
    {
        $m = $this->message;
        $m->setMessageNum($this->getAttr('Msgno'));
        $m->setUid($this->getAttr('message_id'));
        $m->setSubject($this->getAttr('subject'));
        $m->setFrom($this->getEmails($this->getAttr('from', false)));
        $m->setCc($this->getEmails($this->getAttr('cc', false)));
        $m->setBcc($this->getEmails($this->getAttr('bcc', false)));
        $m->setTo($this->getEmails($this->getAttr('to', false)));
        $m->setSize($this->getAttr('Size'));
        $m->setDate($this->getAttr('MailDate'));
        $m->setRead(!$this->getAttr('Unseen'));
        $m->setImportant($this->getAttr('Flagged'));
        $m->setAnswered($this->getAttr('Answered'));
        $m->setAttachments($this->getAttachments());
    }

    /**
     * Sets the main body of the message
     * @param int $options Any options for fetchbody (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @return string
     */
    private function setMessageBody($options = 0)
    {
        $hasHtmlBody = false;
        $hasTextBody = false;


        if (count($this->bodyParts)) {
            foreach ($this->bodyParts as $part) {
                foreach ($part as $i => $section) {
                    if ($section->getSubType() == 'PLAIN') {
                        $hasTextBody = true;
                        $body = $this->decoder->decodeBody($this->imap->fetchBody($this->messageNum, $section->getSection(), $options), $section->getEncoding());
                        $body = $this->encode($section, $body);
                        $this->message->setTextBody($body);
                    }

                    if ($section->getSubType() == 'HTML') {
                        $hasHtmlBody = true;
                        $body = $this->decoder->decodeBody($this->imap->fetchBody($this->messageNum, $section->getSection(), $options), $section->getEncoding());
                        $body = $this->encode($section, $body);
                        $this->message->setHtmlBody($body);
                    }
                }
            }
        }

        if (!$hasHtmlBody && $hasTextBody) {
            $this->message->setHtmlBody(nl2br($this->message->getTextBody()));
        }
    }

    /**
     * Encodes the body to the set encoding (by default UTF-8)
     * @param $section
     * @param $body
     * @return mixed|string
     */
    protected function encode($section, $body)
    {
        $charset = ($section->getCharset()) ? $section->getCharset() : mb_detect_encoding($body);
        if ($charset) {
            return mb_convert_encoding($body, $this->outputEncoding, $charset);
        } elseif ($this->outputEncoding == "UTF-8") {
            return utf8_encode($body);
        }

        return mb_convert_encoding($body, $this->outputEncoding);
    }

    /**
     * Get the E-mail attachment details for the given message number
     * @param int $messageNo The message number
     * @return ImapAttachmentCollection
     */
    public function getAttachments()
    {
        $attachments = new ImapAttachmentCollection();

        if (isset($this->structure->parts) && count($this->structure->parts)) {
            foreach ($this->structure->parts as $i => $part) {
                if ($part->ifdparameters) {
                    foreach ($part->dparameters as $param) {
                        if ($param->attribute == 'FILENAME') {
                            $attachments->add(new ImapAttachment($param->value, ($i + 1), $part->encoding, (array)$part));
                        }
                    }
                }
            }
        }

        return $attachments;
    }

    /**
     * Returns an EmailCollection from the Email headers
     * @param $emails
     * @return EmailCollection
     */
    protected function getEmails($emails)
    {
        $emailCollection = new EmailCollection();
        if ($emails) {
            foreach ($emails as $key => $email) {
                $mailbox = $this->decoder->decodeHeader($email->mailbox);
                $host = $this->decoder->decodeHeader($email->host);
                $personal = (isset($email->personal)) ? $this->decoder->decodeHeader($email->personal) : null;
                $this->addEmailAddress($emailCollection, $mailbox, $host, $personal, $email);
            }
        }
        return $emailCollection;
    }

    /**
     * Returns the given attribute from the message array
     * @param $attribute
     * @return null
     */
    protected function getAttr($attribute, $decode = true)
    {
        if ($decode) {
            return (isset($this->headers[$attribute])) ? $this->decoder->decodeHeader($this->headers[$attribute]) : null;
        }

        return (isset($this->headers[$attribute])) ? $this->headers[$attribute] : null;
    }

    /**
     * @param $emailCollection
     * @param $mailbox
     * @param $host
     * @param $personal
     * @param $email
     */
    protected function addEmailAddress($emailCollection, $mailbox, $host, $personal, $email)
    {
        $emailCollection->add(new EmailAddress($mailbox, $host, $personal, $email));
    }
}