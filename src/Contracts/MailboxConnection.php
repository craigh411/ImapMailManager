<?php

namespace Humps\MailManager\Contracts;
use Humps\MailManager\Components\Mailbox;


/**
 * Handles the connection to the imap server
 * Class ImapConnector
 * @package Humps\MailManager
 */
interface MailboxConnection
{
    /**
     * Closes the connection to the mail server
     * @param int $options
     * @return bool
     */
    public function closeConnection($options = 0);

    /**
     * Returns the current connection resource
     * @return resource
     */
    public function getConnection();

    /**
     * Resets/Refreshes the connection to the mail server
     * @return bool
     */
    public function refresh();

    /**
     * Returns the connections Mailbox object
     * @return Mailbox
     */
    public function getMailbox();
}