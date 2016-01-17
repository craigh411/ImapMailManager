<?php


namespace Humps\MailManager;

use Exception;

use Humps\MailManager\Components\Mailbox;
use Humps\MailManager\Contracts\MailboxConnection;

/**
 * Handles the connection to the imap server
 * Class ImapConnector
 * @package Humps\MailManager
 */
class ImapConnection implements MailboxConnection
{
    protected $connection;

    /**
     * @var Mailbox
     */
    private $mailbox;

    function __construct(Mailbox $mailbox){
        $this->mailbox = $mailbox;
        if (!$this->connection = $this->connect()) {
            throw new Exception('Unable to connect to to mailbox: ' . $this->mailbox->getServer());
        }

/*        // Get the mailbox
        $mailbox = new Mailbox();
        // Connect to the mailbox
        $connection = new ImapConnection($mailbox);
        // Get the imap handler
        $imap = new ImapHandler($connection);
        // Get the mailbox
        $mailboxService = new MailboxService($imap);*/
    }



    /**
     * Opens the connection to the mailbox
     * @return resource
     */
    protected function connect()
    {
        return imap_open($this->mailbox->getMailboxName(), $this->mailbox->getUsername(), $this->mailbox->getPassword());
    }

    /**
     * Closes the connection to the mail server
     * @param bool $deleteFlaggedEmails If set to true all emails flagged for deletion will be removed
     * @return bool
     */
    public function closeConnection($options = 0)
    {
        return imap_close($this->connection, $options);
    }

    /**
     * Returns the current connection resource
     * @return resource
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Resets/Refreshes the connection to the mail server
     * @return bool
     */
    public function refresh()
    {
        return imap_reopen($this->connection, $this->mailbox->getMailboxName());
    }

    /**
     * Returns the connections Mailbox object
     * @return Mailbox
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }
}