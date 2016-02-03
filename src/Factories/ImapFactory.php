<?php


namespace Humps\MailManager\Factories;


use Exception;
use Humps\MailManager\Components\Mailbox;
use Humps\MailManager\ImapConnection;
use Humps\MailManager\ImapHandler;

class ImapFactory
{
    public static function create($folder = 'INBOX', $configPath = 'imap_config/config.php')
    {
        $mailbox = static::getMailbox($folder, $configPath);
        $connection = static::getConnection($mailbox);
        $mailbox->setFolder($folder);

        return new ImapHandler($connection);
    }

    /**
	 * Returns the Mailbox object for the given folder and config
	 * @param string $folder
     * @param string $configPath
     * @return Mailbox
     * @throws Exception
     */
    protected static function getMailbox($folder, $configPath)
    {
        return MailboxFactory::create($folder, $configPath);
    }

    /**
	 * Returns the ImapConnection
     * @param Mailbox $mailbox
     * @return ImapConnection
     */
    protected static function getConnection(Mailbox $mailbox)
    {
        return new ImapConnection($mailbox);
    }
}