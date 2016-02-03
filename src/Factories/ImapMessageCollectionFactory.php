<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\Collections\ImapMessageCollection;
use Humps\MailManager\Contracts\Decoder;
use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\MessageDecoder;

class ImapMessageCollectionFactory
{
    /**
     * Gets the message collection for the given Message Numbers
     * @param array $messageNumbers
	 * @param Imap $imap
     * @param bool $excludeBody
     * @param bool $peek
	 * @param Decoder $decoder
     * @param string $outputEncoding
     * @return ImapMessageCollection
     */
    public static function create(array $messageNumbers, Imap $imap, $excludeBody = true, $peek = true, Decoder $decoder = null, $outputEncoding = "UTF-8")
    {
        $decoder = (!$decoder) ? new MessageDecoder() : $decoder;
        $messages = new ImapMessageCollection();
        if (count($messageNumbers)) {
            foreach ($messageNumbers as $messageNum) {
                static::addImapMessage($imap, $excludeBody, $peek, $outputEncoding, $messages, $decoder, (int)$messageNum);
            }
        }
        return $messages;
    }

    /**
     * Adds a message to the collection
     * @param Imap $imap
     * @param bool $excludeBody
     * @param bool $peek
     * @param string $outputEncoding
     * @param ImapMessageCollection $messages
	 * @param Decoder $decoder
     * @param int $messageNum
     */
    protected static function addImapMessage(Imap $imap, $excludeBody, $peek, $outputEncoding, ImapMessageCollection $messages, Decoder $decoder, $messageNum)
    {
        $messages->add(ImapMessageFactory::create($messageNum, $imap, $excludeBody, $peek, $decoder, $outputEncoding));
    }
}