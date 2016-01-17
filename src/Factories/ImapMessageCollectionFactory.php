<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\Collections\ImapMessageCollection;
use Humps\MailManager\Contracts\Imap;

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
    public static function create(array $messageNumbers, Imap $imap, $excludeBody = true, $peek = true, $outputEncoding = "UTF-8")
    {
        $messages = new ImapMessageCollection();
        if (count($messageNumbers)) {
            foreach ($messageNumbers as $messageNum) {
                static::addImapMessage($imap, $excludeBody, $peek, $outputEncoding, $messages, $messageNum);
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
    protected static function addImapMessage(Imap $imap, $excludeBody, $peek, $outputEncoding, $messages, $messageNum)
    {
        $messages->add(ImapMessageFactory::create($messageNum, $imap, $excludeBody, $peek, $outputEncoding));
    }
}