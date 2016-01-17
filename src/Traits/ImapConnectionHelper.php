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
        if(isset($this->imap)) {
            return $this->imap->getConnection();
        }

        throw new Exception('getConnection() in ImapConnectionHelper Trait expects an Imap class object named $imap to have been instantiated.');
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