<?php


namespace Humps\MailManager;

use Humps\MailManager\Contracts\Imap;

/**
 * Wraps the native php imap functions.
 * Class Imap
 * @package Humps\MailManager
 */
class ImapHandler implements Imap
{

    protected $connection;

    /**
     * Imap constructor.
     * @param ImapConnection $connection
     */
    function __construct(ImapConnection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * Sets the given flag on the given messages
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @param string $flag Either \Seen, \Answered, \Flagged, \Deleted, or \Draft
     * @return bool
     */
    public function setFlag($messageList, $flag)
    {
        return imap_setflag_full($this->connection->getConnection(), $messageList, $flag);
    }

    /**
     * Fetches the structure of the E-mail (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @param int $messageNo The message number
     * @return array
     */
    public function fetchStructure($messageNo)
    {
        return @imap_fetchstructure($this->connection->getConnection(), $messageNo);
    }

    /**
     * Returns the headers for a given message
     * @param $messageNo
     * @return string
     */
    public function fetchHeader($messageNo)
    {
        return imap_fetchheader($this->connection->getConnection(), $messageNo);
    }

    /**
     * Fetches the given body part (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @param int $messageNo The message number
     * @param string $part The part (e.g. 1.2). This will be returned from <a href="#method_fetchBodyParts">fetchBodyParts()</a>
     * @param int $options Any options to pass to imap_fetchbody (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>);
     * @return string
     */
    public function fetchBody($messageNo, $part, $options = 0)
    {
        return imap_fetchbody($this->connection->getConnection(), $messageNo, $part, $options);
    }

    /**
     * Returns status information on the current mailbox. see: imap_status() (http://php.net/manual/en/function.imap-status.php)
	 * @param int $options
     * @return object
     */
    public function getStatus($options = SA_ALL)
    {
        return imap_status($this->connection->getConnection(), $this->connection->getMailbox()->getMailboxName(), $options);
    }

    /**
     * Returns the number of messages in the mailbox
     * @return int
     */
    public function getMessageCount()
    {
        return imap_num_msg($this->connection->getConnection());
    }


    /**
     * Sets the given messages for deletion
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function setMessagesForDeletion($messageList)
    {
        return imap_delete($this->connection->getConnection(), $messageList);

    }

	/**
	 * Moves the messages to the given folder
	 * @param string $messageList
	 * @param string $folder
	 * @return bool
	 */
    public function moveMessages($messageList, $folder)
    {
        return imap_mail_move($this->connection->getConnection(), $messageList, $folder);
    }

    /**
     * Deletes all messages set for deletion
     * @return bool
     */
    public function deleteMessages()
    {
        return imap_expunge($this->connection->getConnection());
    }


    /**
     * Returns the message headers
     * @param $messageNo
     * @return object
     */
    public function getMessageHeaders($messageNo)
    {
        return imap_headerinfo($this->connection->getConnection(), $messageNo);
    }

    /**
     * Returns the sorted results
     * @param string|array $criteria
     * @param int $sortBy
     * @param bool $reverse
	 * @param int $options
     * @return array
     */
    public function sort($criteria, $sortBy, $reverse, $options = 0)
    {
        return imap_sort($this->connection->getConnection(), $sortBy, $reverse, $options, $criteria);
    }

    /**
     * Gets the folders matching the given pattern
     * @param $mailbox
     * @param $pattern
     * @return array
     */
    public function getFolders($mailbox, $pattern)
    {
        return imap_getmailboxes($this->connection->getConnection(), $mailbox, $pattern);
    }

    /**
     * Renames the current folder
     * @param string $oldName
	 * @param string $newName
     * @return bool
     */
    public function renameFolder($oldName, $newName)
    {
        return imap_renamemailbox($this->connection->getConnection(), $oldName, $newName);
    }

    /**
     * Get the message number for the uid.
     * @param $uid
     * @return int
     */
    public function getMessageNumber($uid)
    {
        return imap_msgno($this->connection->getConnection(), $uid);
    }

    /**
     * Wrapper for imap_createmailbox() function.
     * @param $mailbox
     * @return bool
     */
    public function createMailbox($mailbox)
    {
        return imap_createmailbox($this->connection->getConnection(), $mailbox);
    }

    /**
     * Wrapper for imap_deletemailbox().
     * @param $mailbox
     * @return bool
     */
    public function deleteMailbox($mailbox)
    {
        return imap_deletemailbox($this->connection->getConnection(), $mailbox);
    }


    /**
     * Wrapper for imap_utf7_encode().
     * @param string $mailbox
     * @return string
     */
    public function utf7Encode($mailbox)
    {
        return imap_utf7_encode($mailbox);
    }

    /**
     * Wrapper for imap_utf7_encode().
     * @param string $mailbox
     * @return string
     */
    public function utf7Decode($mailbox)
    {
        return imap_utf7_decode($mailbox);
    }


    /**
     * Adds the given message to the current folder. see: imap_append() (http://php.net/manual/en/function.imap-append.php)
     * @param string $message
     * @param int $options
     * @param string $internalDate
     * @return bool
     */
    public function addMessage($message, $options = null, $internalDate = null)
    {
        return imap_append($this->connection->getConnection(), $this->connection->getMailbox()->getMailboxName(), $message, $options, $internalDate);
    }

    /**
     * Returns all the imap error messages that have occurred
     * @return array
     */
    public function getErrors()
    {
        return imap_errors();
    }

    /**
     * Returns all the imap alert messages that have occurred
     * @return array
     */
    public function getAlerts()
    {
        return imap_alerts();
    }

    /**
     * Returns the ImapConnection
     * @return ImapConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}