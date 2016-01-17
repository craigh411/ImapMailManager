<?php


namespace Humps\MailManager;

use Carbon\Carbon;
use Exception;
use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\Traits\ImapConnectionHelper;


/**
 * Handles tasks on the Mailbox itself.
 *
 * Class MailboxManager
 * @package Humps\MailManager
 */
class ImapMailboxService
{
    use ImapConnectionHelper;

    protected $imap;
    protected $aliases;

    function __construct(Imap $imap, $aliasFile = 'imap_config/aliases.php')
    {
        $this->imap = $imap;
        if (file_exists($aliasFile)) {
            $this->aliases = include $aliasFile;
        }
    }

    /**
     * Returns the current config
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Gets the folder by it's alias, if the folder cannot be found it returns the passed parameter
     * @param $alias
     * @return null
     */
    public function getFolderByAlias($alias)
    {
        return (isset($this->aliases[$alias])) ? $this->aliases[$alias] : $alias;
    }

    /**
     * Returns all messages for the current imap connection
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getAllMessages($sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        return $this->searchMessages('ALL', $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all unread messages for the current imap connection
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getUnreadMessages($sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        return $this->searchMessages('UNSEEN', $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages by the given sender(s) for the current imap connection
     * @param array|string $sender The name or Email of the sender
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getMessagesBySender($sender, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $criteria = ['from' => $sender];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages for the current imap connection with the given search in their subject.
     * @param array|string $search The search for the subject
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getMessagesBySubject($search, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $criteria = ['subject' => $search];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages for the current imap connection with the given cc.
     * @param array|string $cc The name or Email of the sender
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getMessagesByCc($cc, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $criteria = ['cc' => $cc];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages for the current imap connection with the given bcc.
     * @param array|string $bcc The name or Email of the sender
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getMessagesByBcc($bcc, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $criteria = ['bcc' => $bcc];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages for the current imap connection with the given receiver.
     * @param array|string $to The name or Email of the receiver(s)
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getMessagesByReceiver($to, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $criteria = ['to' => $to];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages for the current imap connection sent on the given date.
     * @param string $date The date to search for
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getMessagesOn($date, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        $criteria = ['on' => $date];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages for the current imap connection sent before the given date.
     * @param string $date The date to search for
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getMessagesBefore($date, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        $criteria = ['before' => $date];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages for the current imap connection sent after the given date.
     * @param string $date The date to search for
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getMessagesAfter($date, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        $criteria = ['since' => $date];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages for the current imap connection sent between the given dates.
     * @param string $from The from date to search for
     * @param string $to The to date to search for
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     * @throws Exception
     */
    public function getMessagesBetween($from, $to, $sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);


        if ($from->timestamp > $to->timestamp) {
            throw new Exception('Invalid Date Range: \'from\' date must be earlier than \'to\' date.');
        }

        $criteria = ['since' => $from->format('d-M-Y'), 'before' => $to->addDays(1)->format('d-M-Y')];

        return $this->searchMessages($criteria, $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all read messages for the current imap connection
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @return array An array of message numbers
     */
    public function getReadMessages($sortBy = SORTDATE, $reverse = true)
    {
        return $this->searchMessages('SEEN', $sortBy, $reverse);
    }


    /**
     * Returns all messages marked as important for the current imap connection
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getImportantMessages($sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        return $this->searchMessages('FLAGGED', $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages marked as answered for the current imap connection
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getAnsweredMessages($sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        return $this->searchMessages('ANSWERED', $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Returns all messages marked as unanswered for the current imap connection
     * @param int $sortBy The criteria to sort the messages by
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $peek Whether to mark the message as read. true to peek false to mark as read.
     * @return array An array of message numbers
     */
    public function getUnansweredMessages($sortBy = SORTDATE, $reverse = true, $peek = true)
    {
        return $this->searchMessages('UNANSWERED', $sortBy, $reverse, $this->doPeek($peek));
    }

    /**
     * Search for a message by the given criteria. The criteria can either be a string, such as 'ALL' or an array
     * Where the key is the criteria and the value is a string or array of search terms.
     * see imap_search criteria (<a href="http://php.net/manual/en/function.imap-search.php">http://php.net/manual/en/function.imap-search.php</a>)
     *
     * It's important to note that this performs a logical 'AND' operation, so ['to' => ['foo@bar.com','bar@baz.com']] would return
     * emails to all listed recipients, not any.
     *
     * @param string|array $criteria The search criteria (e.g. 'FROM' for 'from' email address). see imap_search criteria (<a href="http://php.net/manual/en/function.imap-search.php">http://php.net/manual/en/function.imap-search.php</a>)
     * This parameter can also be passed as an array for criteria with arguments e.g ['from' => 'me@example.com', 'to' => 'you@example.com'] and each key can also be passed as an array (e.g. ['to' => ['me@example.com', 'you@example.com']]).
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param int $options Any options to pass through the imap_sort(see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @return array An array or message numbers
     */
    public function searchMessages($criteria, $sortBy = SORTDATE, $reverse = true, $options = 0)
    {
        // Search using the criteria and search terms
        if (is_array($criteria)) {
            // Build the search string from the given criteria array.
            $searchString = $this->buildSearchString($criteria);
        } else {
            // There are no search terms, so just use the criteria (some criteria such as ALL, do not have searches)
            $searchString = strtoupper($criteria);
        }

        return $this->imap->sort($searchString, $sortBy, $reverse, $options);
    }


    /**
     * Flags the given messages as read
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsRead($messageList)
    {
        return $this->imap->setFlag($messageList, '\Seen');
    }

    /**
     * Flags the given messages as important
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsImportant($messageList)
    {
        return $this->imap->setFlag($messageList, '\Flagged');
    }

    /**
     * Flags the given messages as answered
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsAnswered($messageList)
    {
        return $this->imap->setFlag($messageList, '\Answered');
    }

    /**
     * Returns the number of unread messages
     * @return int
     */
    public function getUnreadMessageCount()
    {
        return $this->imap->getStatus(SA_UNSEEN)->unseen;
    }

    /**
     * Deletes all messages from the given folder
     * @param string $folder The folder name
     * @return bool
     */
    public function deleteAllMessages($folder)
    {
        return $this->deleteMessages($folder, '1:*');
    }

    /**
     * Deletes the given messages from the given folder
     * @param $folder
     * @param $messageList
     * @return bool
     * @throws Exception
     */
    public function deleteMessages($folder, $messageList)
    {
        $deleted = false;
        $mailbox = $this->getMailbox();
        $currentFolder = $mailbox->getFolder();
        $folder = $this->getFolderByAlias($folder);
        if ($this->openFolder($folder)) {
            if ($this->imap->setMessagesForDeletion($messageList)) {
                $deleted = $this->imap->deleteMessages();
            }
        }

        // Re-open the connection to the original folder
        if ($currentFolder !== $folder) {
            if (!$this->openFolder($currentFolder)) {
                $this->imap->getConnection()->closeConnection();
                throw new Exception('Unable to re-open folder ' . $currentFolder . ' Connection has been closed');
            }
        }

        return $deleted;
    }

    /**
     * Opens a connection to the given folder
     * @param string $folder The name or alias of the folder to open
     * @return bool Returns true on success, false on failure
     */
    public function openFolder($folder)
    {
        $folder = $this->getFolderByAlias($folder);
        $mailbox = $this->getMailbox();
        $mailbox->setFolder($folder);

        return $this->getConnection()->refresh();
    }


    /**
     * Returns all child folders in the given folder
     * @param $folder
     * @return array
     */
    public function getChildFolders($folder)
    {
        $folder = $this->getFolderByAlias($folder);

        return $this->imap->getFolders($this->getMailbox()->getMailboxName(true) . $folder, '*');
    }

    /**
     * Returns all the folders for the current mailbox
     * @return array
     */
    public function getAllFolders()
    {
        return $this->imap->getFolders($this->getMailbox()->getMailboxName(true), '*');
    }

    /**
     * Searches for folders based on the given pattern
     * @param string $mailboxName The mailbox name
     * @param string $pattern The pattern to search the folders for
     * @return array
     */
    public function searchFolders($mailboxName, $pattern = '*')
    {
        return $this->imap->getFolders($mailboxName, $pattern);
    }

    /**
     * Moves the given messages to the trash folder.
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @param string $folder The name of the trash folder or it's alias
     * @return bool
     */
    public function moveToTrash($messageList, $folder = 'trash')
    {
        return $this->moveMessages($messageList, $folder);
    }

    /**
     * Empties the trash folder
     * @param string $folder
     * @return bool
     */
    public function emptyTrash($folder = 'trash')
    {
        return $this->deleteAllMessages($folder);
    }


    /**
     * @param $messageList
     * @param $folder
     * @return mixed
     */
    public function moveMessages($messageList, $folder)
    {
        $folder = $this->getFolderByAlias($folder);
        return $this->imap->moveMessages($messageList, $folder);
    }


    /**
     * Creates a new folder
     * @param string $folder The name of the folder to create
     * @param bool|null $parent The name of the parent folder
     * @param string $delimiter The delimiter used to separate parent/child folders
     * @return bool true on success, false on failure
     */
    public function createFolder($folder, $parent = null, $delimiter = '.')
    {

        $mailbox = $this->buildMailboxString($folder, $parent, $delimiter);
        return $this->imap->createMailbox($mailbox);
    }


    /**
     * Deletes the given folder
     * @param string $folder The name of the folder to create
     * @param bool|null $parent The name of the parent folder
     * @param string $delimiter The delimiter used to separate parent/child folders
     * @return bool true on success, false on failure
     */
    public function deleteFolder($folder, $parent = null, $delimiter = '.')
    {
        $mailbox = $this->buildMailboxString($folder, $parent, $delimiter);
        return $this->imap->deleteMailbox($mailbox);
    }

    /**
     * Returns true if the folder exists, false if not
     * @param string $folder The name of the folder to create
     * @return bool true on success, false on failure
     */
    public function folderExists($folder)
    {
        $mailbox = $this->getMailbox()->getMailboxName(true);
        return is_array($this->searchFolders($mailbox, $folder));
    }

    /**
     * returns the correct mailbox string for creating and deleting folders, based on the given parameters
     * @param $folder
     * @param $parent
     * @param $delimiter
     * @return string
     */
    protected function buildMailboxString($folder, $parent, $delimiter)
    {
        if ($parent) {
            $parent = $this->getFolderByAlias($parent);
            $folder = $parent . $delimiter . $folder;
        }

        $name = imap_utf7_encode($folder);
        return $mailbox = $this->getMailbox()->getMailboxName(true) . $name;
    }

    /**
     * Builds the search string for imap_sort search criteria.
     * @param array $criteria
     * @return string
     */
    protected function buildSearchString(array $criteria)
    {
        $searchString = '';
        if (count($criteria)) {
            // Builds the search string from the array
            foreach ($criteria as $key => $search) {
                if (is_array($search)) {
                    foreach ($search as $searchTerm) {

                        $searchString .= strtoupper($key) . ' "' . addslashes($searchTerm) . '" ';
                    }
                } else {
                    $searchString .= strtoupper($key) . ' "' . addslashes($search) . '" ';
                }
            }
        }
        return trim($searchString);
    }

    /**
     * Returns FT_PEEK if $peek is true
     * @param bool $peek
     * @return int
     */
    private function doPeek($peek)
    {
        return ($peek) ? FT_PEEK : 0;
    }

    /**
     * Returns a comma delimited message list from the given array.
     * @param array $messageNumbers An array of message Numbers.
     * @return string The message list.
     */
    public static function getMessageList(array $messageNumbers)
    {
        return implode(',', $messageNumbers);
    }

}