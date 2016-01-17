<?php

namespace Humps\MailManager\Contracts;

use Exception;
use Humps\MailManager\Collections\ImapAttachmentCollection;
use Humps\MailManager\Collections\ImapMessageCollection;
use Humps\MailManager\Collections\MessageCollection;
use Humps\MailManager\Components\Contracts\Message;
use Humps\MailManager\Config;
use Humps\MailManager\Components\ImapMessage;
use Humps\MailManager\Mailbox;

interface MailManager
{
    /**
     * Gets the message by the given message number.
     * @param int $messageNo The message number
     * @param int $options Any options for fetchBody (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @param bool $headersOnly Only retrieve header information (essentially excludes fetching the body when set to true)
     * @return \Humps\MailManager\Components\ImapMessage The Message object!
     */
    public function getMessage($messageNo, $options = 0, $headersOnly = false);

    /**
     * Returns all messages for the given mailbox
     * @param bool $markAsRead
     * @return array An array of Message objects
     */
    public function getAllMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Returns all unread messages
     * @param bool $markAsRead Marks the fetched messages as read when set to true
     * @return array An array of Message objects
     */
    public function getUnreadMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets all messages for the given sender
     * @param string $sender The senders name or email address
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesBySender($sender, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets messages by subject
     * @param string $subject The subject to search for
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesBySubject($subject, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets the messages by CC
     * @param string $cc The name or email to search the cc field for
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesByCC($cc, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets the messages by BCC
     * @param string $bcc The name or email to search the bcc field for
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesByBCC($bcc, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets the messages by the to field
     * @param string $to The name or email to search the to field for
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesByReceiver($to, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets the messages sent on the specified date
     * @param string $date The date  - This is run through Carbon::parse(), so can be any date format supported by Carbon (see: <a href="http://carbon.nesbot.com/docs/#api-instantiation">http://carbon.nesbot.com/docs/#api-instantiation</a>)
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesByDate($date, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets the messages sent before the specified date
     * @param string $date The date  - This is run through Carbon::parse(), so can be any date format supported by Carbon (see: <a href="http://carbon.nesbot.com/docs/#api-instantiation">http://carbon.nesbot.com/docs/#api-instantiation</a>)
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesBefore($date, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets the messages sent between the specified dates
     * @param string $from The from date. This is run through Carbon::parse(), so can be any date format supported by Carbon (see: <a href="http://carbon.nesbot.com/docs/#api-instantiation">http://carbon.nesbot.com/docs/#api-instantiation</a>)
     * @param string $to The to date. This is run through Carbon::parse(), so can be any date format supported by Carbon (see: <a href="http://carbon.nesbot.com/docs/#api-instantiation">http://carbon.nesbot.com/docs/#api-instantiation</a>)
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     * @throws Exception
     */
    public function getMessagesBetween($from, $to, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Gets the messages sent after the specified date
     * @param string $date The date. This is run through Carbon::parse(), so can be any date format supported by Carbon (see: <a href="http://carbon.nesbot.com/docs/#api-instantiation">http://carbon.nesbot.com/docs/#api-instantiation</a>)
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesAfter($date, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Returns all messages marked as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getReadMessages($sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Returns all messages flagged as important (i.e. FLAGGED)
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getImportantMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Returns all messages flagged as answered
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getAnsweredMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Returns all messages flagged as unanswered
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getUnansweredMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false);

    /**
     * Search for a message by the given criteria. The criteria can either be a string, such as 'ALL' or an array
     * Where the key is the criteria and the value is a string or array of search terms.
     * see imap_search criteria (<a href="http://php.net/manual/en/function.imap-search.php">http://php.net/manual/en/function.imap-search.php</a>)
     *
     * It's important to note that this performs a logical 'AND' operation, so ['to' => ['foo@bar.com','bar@baz.com']] would return
     * emails to all recipients, not any.
     *
     * @param string $criteria The search criteria (e.g. 'FROM' for 'from' email address). see imap_search criteria (<a href="http://php.net/manual/en/function.imap-search.php">http://php.net/manual/en/function.imap-search.php</a>)
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param int $messageOptions Any options to pass through the imap_fetch_body (see: <a href="http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return ImapMessageCollection A collection of ImapMessage objects
     */
    public function searchMessages($criteria, $sortBy = SORTDATE, $reverse = true, $messageOptions = 0, $headersOnly = false);


    /**
     * Get the message by the unique identifier
     * @param string $uid
     * @param int $options
     * @param bool $headersOnly
     * @return \Humps\MailManager\Components\ImapMessage the message
     */
    public function getMessageByUid($uid, $options, $headersOnly);

    /**
     * Returns a comma delimited message list from the given array.
     * @param MessageCollection $messages An array of Message objects.
     * @return string The message list.
     * @throws Exception
     */
    public static function getMessageList(MessageCollection $messages);

    /**
     * Returns an array of message numbers for the given messages.
     * @param MessageCollection $messages An array of Message objects
     * @return array
     * @throws Exception
     */
    public static function getMessageNumbers(MessageCollection $messages);

    /**
     * Flags the given messages as read
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsRead($messageList);

    /**
     * Flags the given messages as important
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsImportant($messageList);

    /**
     * Flags the given messages as answered
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsAnswered($messageList);

    /**
     * Returns an array of BodyParts . The array is broken down into sections, so all parts from section 1
     * will be at index 0 (`$bodyParts[0]`), part 2 at index 1 (`$bodyParts[0]`) etc.
     * @param array $structure The structure retrieved from `getStructure()`;
     * @return array An array of <a href="Contracts/BodyPart.html">BodyPart</a> objects.
     */
    public function fetchBodyParts($structure);

    /**
     * Get the E-mail attachment details for the given message number
     * @param int $messageNo The message number
     * @return ImapAttachmentCollection
     */
    public function getAttachments($messageNo);

    /**
     * Gets the embedded images for the given messages and alters the body accordingly
     * Important: This function downloads images to the given path and places them inside an /embedded/{messageNo} folder
     * @param Message $message
     * @return void
     */
    public function getEmbeddedImages(Message &$message);

    /**
     * Download the attachments for the given message number
     * @param int $messageNo The number of the message
     * @param array $filenames An array of filenames you want to download. Leave empty if you want to download all files
     * @param string $path The download path
     * @return string|bool
     */
    public function downloadAttachments($messageNo, $filenames = [], $path = null);

    /**
     * Returns all the folders for the given mailbox
     * @param bool $currentFolder Whether the folders should be retrieved from the current folder or entire mailbox
     * @return array an array of <a href="Folder.html">Folder</a> objects.
     */
    public function getAllFolders($currentFolder = false);

    /**
     * Returns the number of unread messages
     * @return int
     */
    public function getUnreadMessageCount();

    /**
     * Returns the Mailbox object
     * @return Mailbox
     */
    public function getMailbox();

    /**
     * Deletes all messages from the trash folder
     * @param string $folder
     * @return void
     * @throws Exception
     */
    public function emptyTrash($folder = 'trash');

    /**
     * Deletes all messages from the given folder
     * @param string $folder The folder name
     * @throws Exception
     * @return bool
     */
    public function deleteAllMessages($folder);

    /**
     * Opens a connection to the given folder
     * @param string $folder The name or alias of the folder to open
     * @return bool Returns true on success, false on failure
     */
    public function openFolder($folder);

    /**
     * Sets the output encoding to the given encoding. By default the output encoding is
     * UTF-8, so this is only required when you want your output to use a different encoding.
     * @param string $encoding The encoding (see: <a href="http://php.net/manual/en/mbstring.supported-encodings.php">http://php.net/manual/en/mbstring.supported-encodings.php</a>)
     * @return void
     */
    public function setOutputEncoding($encoding);

    /**
     * @param $mailbox
     * @return array
     */
    public function getMailboxFolders($mailbox, $pattern = '*');


    /**
     * Returns the config object
     * @return Config the config object
     */
    public function getConfig();

    /**
     * Creates a new folder
     * @param string $name The name of the folder
     * @param bool $topLevel whether it's a top level folder
     * @param string $delimiter The delimiter used to separate child folders
     * @return bool true on success, false on failure
     */
    public function createFolder($name, $topLevel = true, $delimiter = '.');

    /**
     * Deletes the given folder
     * @param string $name The name of the folder
     * @param bool $topLevel whether it's a top level folder
     * @param string $delimiter The delimiter used to separate child folders
     * @return bool true on success, false on failure
     */
    public function deleteFolder($name, $topLevel = true, $delimiter = '.');
}