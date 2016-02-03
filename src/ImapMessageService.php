<?php


namespace Humps\MailManager;

use Humps\MailManager\Components\Contracts\Attachment;
use Humps\MailManager\Components\ImapBodyPart;
use Humps\MailManager\Contracts\Decoder;
use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\Components\Contracts\Message;
use Humps\MailManager\Traits\ImapConnectionHelper;

class ImapMessageService
{
    use ImapConnectionHelper;

    protected $imap;
    protected $message;
    protected $decoder;

    function __construct(Message $message, Imap $imap, Decoder $decoder = null)
    {
        $this->decoder = (!$decoder) ? new MessageDecoder() : $decoder;
        $this->message = $message;
        $this->imap = $imap;
    }

    /**
     * Downloads the given image
     * @param string $path
     * @param ImapBodyPart $section
     * @return string
     */
    protected function downloadEmbeddedImage($path = '', ImapBodyPart $section)
    {
        $image = $this->getEmbeddedImage($section, $this->message->getMessageNum());
        $downloadPath = $path . $this->getMailbox()->getFolder() . '/' . $this->message->getMessageNum();
        $file = $downloadPath . '/' . $section->getName();

        if (!file_exists($file)) {
            $file = $this->saveFile($downloadPath, $image, $section->getName());
        }

        return $file;
    }

    /**
     * @param ImapBodyPart $section
     * @param int $messageNo
     * @return string
     */
    protected function getEmbeddedImage(ImapBodyPart $section, $messageNo)
    {
        return $this->decoder->decodeBody($this->imap->fetchBody($messageNo, $section->getSection()), $section->getEncoding());
    }

    /**
     * Gets the embedded images for the given messages and alters the body accordingly
     * Important: This function downloads images to the given path and places them inside an /embedded/{messageNo} folder
     * @param string $path
     * @return void
     */
    public function downloadEmbeddedImages($path = '')
    {
        $path = $this->appendSlash($path);
        // First get all images
        $bodyParts = $this->message->getBodyParts();

        if (count($bodyParts)) {
            $body = $this->message->getHtmlBody();
            foreach ($bodyParts as $part) {
                foreach ($part as $i => $section) {
                    $cid = $this->getCid($section);

                    if ($this->cidFoundInEmailBody($cid, $body)) {

                        $file = $this->downloadEmbeddedImage($path, $section);
                        // Update the html body
                        $body = preg_replace("/cid:\s?$cid/", $file, $body);
                    }
                }
            }
            $this->message->setHtmlBody($body);
        }
    }

    /**
     * Appends a slash to the end of a path if it does not have one
     * @param $path
     * @return string
     */
    private function appendSlash($path)
    {
        if (strlen($path) > 0) {
            if (substr($path, -1) !== '/') {
                $path .= '/';
            }
        }

        return $path;
    }

    /**
     * Returns the email id to match against a cid in an email body
     * @param ImapBodyPart $section
     * @return string
     */
    private function getCid(ImapBodyPart $section)
    {
        return preg_quote(preg_replace(['/^</', '/>$/'], '', $section->getId()));
    }

    /**
     * Returns true if there is a cid (id) AND it's cid reference can be found in the email.
     * @param $cid
     * @param $body
     * @return bool
     */
    private function cidFoundInEmailBody($cid, $body)
    {
        return $cid && preg_match("/cid:\s?$cid/", $body);
    }


    /**
     * Downloads the given attachment
     * @param Attachment $attachment The Attachment object
     * @param string $path The download path
     * @return string The downloaded file path
     */
    public function downloadAttachment(Attachment $attachment, $path = '')
    {
        $path = $this->appendSlash($path);

        $file = $this->imap->fetchBody($this->message->getMessageNum(), $attachment->getPart());


        $decodedAttachment = $this->decoder->decodeBody($file,$attachment->getEncoding());


        $mailbox = strtolower($this->getMailbox()->getFolder());
        $path .= $mailbox . '/' . $this->message->getMessageNum();
        $binary = ($attachment->getEncoding() == ENCBINARY) ? true : false;

        return $this->saveFile($path, $decodedAttachment, $attachment->getFilename(), $binary);
    }


    /**
     * Downloads the attachment at the given part
     * @param string $part
     * @param string $path
     * @return bool|string Returns path on success, false if no attachment was found.
     */
    public function downloadAttachmentByPart($part, $path = '')
    {
        $attachments = $this->message->getAttachments();
        if (count($attachments)) {
            foreach ($attachments as $attachment) {
                if ($attachment->getPart() == $part) {
                    return $this->downloadAttachment($attachment, $path);
                }
            }
        }
        return false;
    }

    /**
     * Downloads the attachment by the given filename
     * @param $filename
     * @param string $path
     * @return bool|string Returns path on success, false if no attachment was found.
     */
    public function downloadAttachmentByFilename($filename, $path = '')
    {
        $attachments = $this->message->getAttachments();
        if (count($attachments)) {
            foreach ($attachments as $attachment) {
                if ($attachment->getFilename() == $filename) {
                    return $this->downloadAttachment($attachment, $path);
                }
            }
        }
        return false;
    }

    /**
     * Downloads all attachments for the message
     * @param string $path
     */
    public function downloadAttachments($path = '')
    {
        $attachments = $this->message->getAttachments();
        if (count($attachments)) {
            foreach ($attachments as $attachment) {
                $this->downloadAttachment($attachment, $path);
            }
        }
    }


    /**
     * Saves the file to the given path
     * @param string $path The path to save the file to
     * @param mixed $file The file information to save
     * @param string $fileName The name of the file
     * @param bool $binary Whether this should be saved with the 'b' flag
     * @return string The saved file path
     */
    protected function saveFile($path, $file, $fileName, $binary = true)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // use binary 'b' mode, needed for Windows.
        $mode = ($binary) ? 'w+b' : 'w+';
        $fp = fopen($path . '/' . $fileName, $mode);
        fwrite($fp, $file);
        fclose($fp);

        return $path . '/' . $fileName;
    }
}