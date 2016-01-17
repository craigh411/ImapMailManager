<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\ImapConnection;
use Humps\MailManager\ImapHandler;

class ImapFactory
{
    public static function create($folder = 'INBOX', $configPath = 'imap_config/config.php')
    {
        $mailbox = static::getMailbox($folder, $configPath);
        $connection = new ImapConnection($mailbox);

        if ($folder) {
            $mailbox->setFolder($folder);
        }

        return new ImapHandler($connection);
    }

    /**
     * @param $configPath
     * @return \Humps\MailManager\Components\Mailbox
     * @throws \Exception
     */
    protected static function getMailbox($folder, $configPath)
    {
        return MailboxFactory::create($folder, $configPath);
    }
}