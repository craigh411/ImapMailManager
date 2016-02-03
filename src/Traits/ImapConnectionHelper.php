<?php


namespace Humps\MailManager\Traits;


use Exception;
use Humps\MailManager\Components\Mailbox;
use Humps\MailManager\ImapConnection;

trait ImapConnectionHelper
{
    /**
     * Returns the current ImapConnection object
     * @return ImapConnection
     */
    public function getConnection()
    {
        return $this->imap->getConnection();
    }

    /**
     * Returns the current Mailbox object
     * @return Mailbox
     */
    public function getMailbox()
    {
        return $this->getConnection()->getMailbox();
    }
}