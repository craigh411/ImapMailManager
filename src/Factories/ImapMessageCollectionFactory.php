<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\Collections\ImapMessageCollection;
use Humps\MailManager\Contracts\Decoder;
use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\MessageDecoder;

class ImapMessageCollectionFactory
{
    /**
     * Gets the message collection for the given Id's
     * @param array $messageNumbers
     * @param bool $excludeBody
     * @param bool $peek
     * @param string $outputEncoding
     * @return ImapMessageCollection
     */
    public static function create(array $messageNumbers, Imap $imap, $excludeBody = true, $peek = true, Decoder $decoder = null, $outputEncoding = "UTF-8")
    {
        $decoder = (!$decoder) ? new MessageDecoder() : $decoder;
        $messages = new ImapMessageCollection();
        if (count($messageNumbers)) {
            foreach ($messageNumbers as $messageNum) {
                static::addImapMessage($imap, $excludeBody, $peek, $outputEncoding, $messages, $decoder, $messageNum);
            }
        }
        return $messages;
    }

    /**
     * Adds a message to the collection
     * @param Imap $imap
     * @param $excludeBody
     * @param $peek
     * @param $outputEncoding
     * @param $messages
     * @param $messageNum
     */
    protected static function addImapMessage(Imap $imap, $excludeBody, $peek, $outputEncoding, $messages, Decoder $decoder, $messageNum)
    {
        $messages->add(ImapMessageFactory::create($messageNum, $imap, $excludeBody, $peek, $decoder, $outputEncoding));
    }
}