<?php

namespace Humps\MailManager;

use Carbon\Carbon;
use Exception;
use Humps\MailManager\Collections\AttachmentCollection;
use Humps\MailManager\Collections\FolderCollection;
use Humps\MailManager\Collections\ImapMessageCollection;
use Humps\MailManager\Collections\MessageCollection;
use Humps\MailManager\Contracts\MailManager;
use Humps\MailManager\Contracts\Message;
use Humps\MailManager\Factories\MailboxFactory;
use Humps\MailManager\Factories\ImapMessageFactory as MessageFactory;

class ImapMailManager implements MailManager
{

    protected $mailboxName;
    protected $connection;
    protected $config;
    protected $mailbox;
    protected $encoded;
    protected $outputEncoding;

    /**
     * MailManager constructor.
     * @param string $folder The name of the mailbox folder to open or it's alias (defaults to 'INBOX')
     * @param string $configFile The path to the config file (defaults to 'imap_config.php' in current dir)
     * @throws Exception
     */
    public function __construct($folder = 'INBOX', $configFile = 'imap_config.php')
    {
        try {
            $this->mailbox = MailboxFactory::create($configFile);
            $this->mailbox->setFolder($this->getFolderNameByAlias($folder));
            $this->mailboxName = $this->createImapMailbox($this->mailbox);
            if ($this->connection = $this->connect()) {
                $this->loadConfig($configFile);
                $this->encoded = false;
                $this->outputEncoding = 'UTF-8';
            } else {
                throw new Exception('Unable to connect to to mailbox: ' . $this->mailboxName);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Creates the imap mailbox from the loaded config file
     * @param Mailbox $mb
     * @return string
     */
    private function createImapMailbox(Mailbox $mb)
    {
        $mailboxName = '{';
        $mailboxName .= $mb->getServer();
        $mailboxName .= ($mb->getPort()) ? ':' . $mb->getPort() : '';
        $mailboxName .= ($mb->isSsl()) ? '/imap/ssl' : '';
        $mailboxName .= (!$mb->isValidateCert()) ? '/novalidate-cert' : '';
        $mailboxName .= '}';
        $mailboxName .= ($mb->getFolder()) ? $mb->getFolder() : '';

        $mailboxName = $this->encodeMailboxName($mailboxName);

        return $mailboxName;
    }

    /**
     * Encodes the mailbox name when non ASCII
     * @param $mailboxName
     * @return string
     */
    private function encodeMailboxName($mailboxName)
    {
        // Check for non-printable ascii characters
        if (!mb_check_encoding($mailboxName, 'ASCII')) {
            $this->encoded = true;
            $mailboxName = imap_utf7_encode($mailboxName);
        }

        return $mailboxName;
    }

    /**
     * Opens the connection to the mailbox
     * @return resource
     */
    protected function connect()
    {
        return imap_open($this->mailboxName, $this->mailbox->getUsername(), $this->mailbox->getPassword());
    }

    /**
     * Loads the config file in to an array
     * @param $config
     */
    protected function loadConfig($config)
    {
        if (file_exists($config)) {
            $this->config = include $config;
        } else {
            $this->config = [];
        }

    }

    /**
     * Gets the message by the given message number.
     * @param int $messageNo The message number
     * @param int $options Any options for fetchBody (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @param bool $headersOnly Only retrieve header information (essentially excludes fetching the body when set to true)
     * @return ImapMessage The Message object!
     */
    public function getMessage($messageNo, $options = 0, $headersOnly = false)
    {
        $headers = $this->getMessageHeaders($messageNo);
        $message = MessageFactory::create($headers);

        if (!$headersOnly) {
            $this->setMessageBody($message, $options);
            $this->getEmbeddedImages($message);
        }

        $message->setAttachments($this->getAttachments($messageNo));

        return $message;
    }

    /**
     * Returns all messages for the given mailbox
     * @param bool $markAsRead
     * @return array An array of Message objects
     */
    public function getAllMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        return $this->searchMessages('ALL', $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Returns all unread messages
     * @param bool $markAsRead Marks the fetched messages as read when set to true
     * @return array An array of Message objects
     */#
    public function getUnreadMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        return $this->searchMessages('UNSEEN', $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Gets all messages for the given sender
     * @param string $sender The senders name or email address
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesBySender($sender, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $criteria = ['from' => $sender];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Gets messages by subject
     * @param string $subject The subject to search for
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesBySubject($subject, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $criteria = ['subject' => $subject];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Gets the messages by CC
     * @param string $cc The name or email to search the cc field for
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesByCC($cc, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $criteria = ['cc' => $cc];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Gets the messages by BCC
     * @param string $bcc The name or email to search the bcc field for
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesByBCC($bcc, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $criteria = ['bcc' => $bcc];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Gets the messages by the to field
     * @param string $to The name or email to search the to field for
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesByReceiver($to, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $criteria = ['to' => $to];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Gets the messages sent on the specified date
     * @param string $date The date  - This is run through Carbon::parse(), so can be any date format supported by Carbon (see: <a href="http://carbon.nesbot.com/docs/#api-instantiation">http://carbon.nesbot.com/docs/#api-instantiation</a>)
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesByDate($date, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        $criteria = ['on' => $date];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Gets the messages sent before the specified date
     * @param string $date The date  - This is run through Carbon::parse(), so can be any date format supported by Carbon (see: <a href="http://carbon.nesbot.com/docs/#api-instantiation">http://carbon.nesbot.com/docs/#api-instantiation</a>)
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesBefore($date, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        $criteria = ['before' => $date];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

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
    public function getMessagesBetween($from, $to, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);


        if ($from->timestamp > $to->timestamp) {
            throw new Exception('Invalid Date Range: \'from\' date must be earlier than \'to\' date.');
        }

        $criteria = ['since' => $from->format('d-M-Y'), 'before' => $to->addDays(1)->format('d-M-Y')];

        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Gets the messages sent after the specified date
     * @param string $date The date. This is run through Carbon::parse(), so can be any date format supported by Carbon (see: <a href="http://carbon.nesbot.com/docs/#api-instantiation">http://carbon.nesbot.com/docs/#api-instantiation</a>)
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getMessagesAfter($date, $markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        $criteria = ['since' => $date];
        return $this->searchMessages($criteria, $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Returns all messages marked as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getReadMessages($sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        return $this->searchMessages('SEEN', $sortBy, $reverse, 0, $headersOnly);
    }


    /**
     * Returns all messages flagged as important (i.e. FLAGGED)
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getImportantMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        return $this->searchMessages('FLAGGED', $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }


    /**
     * Returns all messages flagged as answered
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getAnsweredMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        return $this->searchMessages('ANSWERED', $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

    /**
     * Returns all messages flagged as unanswered
     * @param bool $markAsRead Set the fetched messages as read/seen
     * @param int $sortBy The criteria to sort by (see: <a href="http://php.net/manual/en/function.imap-sort.php">http://php.net/manual/en/function.imap-sort.php</a>)
     * @param bool $reverse Whether the sort should be in reverse order
     * @param bool $headersOnly Return headers only, don't fetch the message body.
     * @return array An array of Message objects
     */
    public function getUnansweredMessages($markAsRead = false, $sortBy = SORTDATE, $reverse = true, $headersOnly = false)
    {
        return $this->searchMessages('UNANSWERED', $sortBy, $reverse, $this->isMarkAsRead($markAsRead), $headersOnly);
    }

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
    public function searchMessages($criteria, $sortBy = SORTDATE, $reverse = true, $messageOptions = 0, $headersOnly = false)
    {

        // Search using the criteria and search terms
        if (is_array($criteria)) {
            $messageIds = [];
            // Build the search string from the given criteria array.
            $searchString = $this->buildSearchString($criteria);

            if ($found = $this->sort($searchString, $sortBy, $reverse)) {
                $messageIds = $found;
            }
        } else {
            // There are no search terms, so just use the criteria (some criteria such as ALL, do not have searches)
            $criteria = strtoupper($criteria);
            $messageIds = $this->sort($criteria, $sortBy, $reverse);
        }

        // Create the MessageCollection
        $messages = new ImapMessageCollection();
        if (count($messageIds)) {
            foreach ($messageIds as $messageId) {
                $messages->add($this->getMessage($messageId, $messageOptions, $headersOnly));
            }
        }

        return $messages;
    }

    /**
     * Get the message by the unique identifier
     * @param string $uid the unique identifier
     * @return Message the message
     */
    public function getMessageByUid($uid)
    {
        return $this->getMessage($this->getMessageNumber($uid));
    }

    /**
     * Returns a comma delimited message list from the given array.
     * @param MessageCollection $messages An array of Message objects.
     * @return string The message list.
     * @throws Exception
     */
    public static function getMessageList(MessageCollection $messages)
    {
        return implode(',', self::getMessageNumbers($messages));
    }

    /**
     * Returns an array of message numbers for the given messages.
     * @param MessageCollection $messages An array of Message objects
     * @return array
     * @throws Exception
     */
    public static function getMessageNumbers(MessageCollection $messages)
    {
        $messageNos = [];
        if (count($messages)) {
            foreach ($messages as $message) {
                if ($message instanceof Message) {
                    $messageNos[] = $message->getMessageNum();
                } else {
                    throw new Exception('array of Message objects expected. ' . get_class($message) . ' Received');
                }
            }
        }
        return $messageNos;
    }


    /**
     * Flags the given messages as read
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsRead($messageList)
    {
        return $this->setFlag($messageList, '\Seen');
    }

    /**
     * Flags the given messages as important
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsImportant($messageList)
    {
        return $this->setFlag($messageList, '\Flagged');
    }

    /**
     * Flags the given messages as answered
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function flagAsAnswered($messageList)
    {
        return $this->setFlag($messageList, '\Answered');
    }

    /**
     * Sets the given flag on the given messages
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @param string $flag Either \Seen, \Answered, \Flagged, \Deleted, or \Draft
     * @return bool
     */
    public function setFlag($messageList, $flag)
    {
        return imap_setflag_full($this->connection, $messageList, $flag);
    }

    /**
     * Sets the main body of the message
     * @param Message $message
     * @param int $options Any options for fetchbody (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @return string
     */
    private function setMessageBody(Message $message, $options = 0)
    {
        $messageNo = $message->getMessageNum();
        $structure = $this->fetchStructure($messageNo);
        $bodyParts = $this->fetchBodyParts($structure);


        $hasHtmlBody = false;
        $hasTextBody = false;

        if (count($bodyParts)) {
            foreach ($bodyParts as $part) {
                foreach ($part as $i => $section) {

                    if ($section->getSubType() == 'PLAIN') {
                        $hasTextBody = true;
                        $body = $this->decode($section->getEncoding(), $this->fetchBody($messageNo, $section->getSection(), $options));
                        $body = $this->encode($section, $body);
                        $message->setTextBody($body);
                    }

                    if ($section->getSubType() == 'HTML') {
                        $hasHtmlBody = true;
                        $body = $this->decode($section->getEncoding(), $this->fetchBody($messageNo, $section->getSection(), $options));
                        $body = $this->encode($section, $body);
                        $message->setHtmlBody($body);
                    }
                }
            }
        }

        if (!$hasHtmlBody && $hasTextBody) {
            $message->setHtmlBody(nl2br($message->getTextBody()));
        }
    }

    /**
     * Fetches the structure of the E-mail (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @param int $messageNo The message number
     * @return object | bool
     */
    public function fetchStructure($messageNo)
    {
        return imap_fetchstructure($this->connection, $messageNo);
    }

    /**
     * @param int $messageNo The number of the message
     * @param int $options Any options to pass in imap_fetchBody() (see: <a href=" http://php.net/manual/en/function.imap-fetchbody.php">http://php.net/manual/en/function.imap-fetchbody.php</a>)
     * @return array An array of BodyPart objects
     */

    /**
     * Returns an array of BodyParts. The array is broken down into sections, so all parts from section 1
     * will be at index 0 ($bodyParts[0]), part 2 at index 1 ($bodyParts[1]) etc.
     *
     * @param array $structure The structure retrieved from getStructure();
     * @param array $parts The array being returned.
     * @param array $sections The current section number as array [1,1,1] is '1.1.1'
     * @return array An array of <a href="Contracts/BodyPart.html">BodyPart</a> objects
     */
    protected function flattenBodyParts($structure, array $parts = [], array $sections = [])
    {
        // Append 1 to the sections array as we are in the next section on each call.
        $sections[] = 1;
        if (isset($structure->parts) && count($structure->parts)) {
            foreach ($structure->parts as $i => $part) {
                if (isset($part->parts)) {
                    // We have more parts, lets get them
                    $parts = $this->flattenBodyParts($part, $parts, $sections);
                } else {
                    // Make the last element of array the loop number, that's the final section we are in!
                    // e.g. 1.1.2
                    $sections[count($sections) - 1] = $i + 1;
                    // Break array in to the main sections
                    $section = implode(".", $sections);
                    $bodyPart = new ImapBodyPart($part->type, $part->encoding, $part->subtype, $section);
                    $this->setParams($part, $bodyPart);
                    $this->setDispositionParams($part, $bodyPart);
                    if (isset($part->id)) {
                        $bodyPart->setId($part->id);
                    }

                    $parts[$sections[0] - 1][] = $bodyPart;
                }
            }
        } elseif (isset($structure) && count($structure)) {
            // We only have 1 part, so add all the sections.
            $parts[0][] = new ImapBodyPart($structure->type, $structure->encoding, $structure->subtype, 1);
        }

        return $parts;
    }

    /**
     * Returns an array of BodyParts . The array is broken down into sections, so all parts from section 1
     * will be at index 0 (`$bodyParts[0]`), part 2 at index 1 (`$bodyParts[0]`) etc.
     * @param array $structure The structure retrieved from `getStructure()`;
     * @return array An array of <a href="Contracts/BodyPart.html">BodyPart</a> objects.
     */
    public function fetchBodyParts($structure)
    {
        return $this->flattenBodyParts($structure);
    }

    /**
     * Returns the headers for a given message
     * @param $messageNo
     * @return string
     */
    public function fetchHeader($messageNo)
    {
        return imap_fetchheader($this->connection, $messageNo);
    }

    /**
     * Sets returned name and charset params on the BodyPart object such if they exist.
     * @param $part
     * @param $bodyPart
     * @return mixed
     */
    private function setParams($part, &$bodyPart)
    {
        // Check for charset and embedded files (these will be in parameters)
        if ($part->ifparameters) {
            foreach ($part->parameters as $param) {
                if ($param->attribute == 'CHARSET') {
                    $bodyPart->setCharset($param->value);
                }
                if ($param->attribute == 'NAME') {
                    if ($part->type == TYPEIMAGE) {
                        $bodyPart->setEmbedded(true);
                    }
                    $bodyPart->setName($param->value);
                }
            }
        }
    }

    /**
     * Decodes the given body with the given encoding.
     * @param int $encoding
     * @param string $body
     * @return string The decoded body
     */
    protected function decode($encoding, $body)
    {
        switch ($encoding) {
            case ENCBASE64:
                return imap_base64($body);
            case ENCQUOTEDPRINTABLE:
                return imap_qprint($body);
            case ENCBINARY:
                return imap_binary($body);
            default:
                return EmailDecoder::decodeBody($body);
        }
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
        return imap_fetchbody($this->connection, $messageNo, $part, $options);
    }

    /**
     * Get the E-mail attachment details for the given message number
     * @param int $messageNo The message number
     * @return AttachmentCollection
     */
    public function getAttachments($messageNo)
    {
        $attachments = new AttachmentCollection();
        $structure = $this->fetchStructure($messageNo);

        if (isset($structure->parts) && count($structure->parts)) {
            foreach ($structure->parts as $i => $part) {
                if ($part->ifdparameters) {
                    foreach ($part->dparameters as $param) {
                        if ($param->attribute == 'FILENAME') {
                            $attachments->add(new Attachment($param->value, ($i + 1), $part->encoding, (array)$part));
                        }
                    }
                }
            }
        }

        return $attachments;
    }

    /**
     * Gets the embedded images for the given messages and alters the body accordingly
     * Important: This function downloads images to the given path and places them inside an /embedded/{messageNo} folder
     * @param Message $message
     * @return void
     */
    public function getEmbeddedImages(Message &$message)
    {

        // Only do this if download_embedded_images is set to true
        if (isset($this->config['download_embedded_images']) && $this->config['download_embedded_images']) {

            // First get all images
            $messageNo = $message->getMessageNum();
            $structure = $this->fetchStructure($messageNo);
            $bodyParts = $this->fetchBodyParts($structure);

            if (count($bodyParts)) {
                foreach ($bodyParts as $part) {
                    foreach ($part as $i => $section) {
                        $cid = $this->getCid($section);
                        $body = $message->getHtmlBody();


                        if ($this->cidFoundInEmailBody($cid, $body)) {
                            $file = $this->downloadEmbeddedImage($section, $messageNo);
                            // Update the html body
                            $body = preg_replace("/cid:\s?$cid/", $file, $body);
                            $message->setHtmlBody($body);
                        }
                    }
                }
            }
        }
    }

    /**
     * Returns the FT_PEEK flag if true
     * @param $markAsRead
     * @return int
     */
    private function isMarkAsRead($markAsRead)
    {
        $options = ($markAsRead) ? 0 : FT_PEEK;
        return $options;
    }

    /**
     * Download the attachments for the given message number
     * @param int $messageNo The number of the message
     * @param array $filenames An array of filenames you want to download. Leave empty if you want to download all files
     * @param string $path The download path
     * @return string|bool
     */
    public function downloadAttachments($messageNo, $path = '', $filenames = [])
    {
        // if filename passed as a string, add it to an array.
        if (!is_array($filenames)) {
            $filenames = [$filenames];
        }

        // Append '/' to path if it hasn't been already
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        $attachments = $this->getAttachments($messageNo);

        if (count($attachments)) {

            foreach ($attachments as $attachment) {
                if (in_array($attachment->getFilename(), $filenames) || count($filenames) === 0) {
                    $file = $this->fetchBody($messageNo, $attachment->getPart());
                    $decodedAttachment = $this->decode($attachment->getEncoding(), $file);

                    $mailbox = strtolower($this->getFolderName());
                    $path .= $mailbox . '/' . $messageNo;
                    $binary = ($attachment->getEncoding() == ENCBINARY) ? true : false;
                    return $this->saveFile($path, $decodedAttachment, $attachment->getFilename(), $binary);
                }
            }
        }
        return false;
    }

    /**
     * Saves the file to the given path
     * @param $messageNo
     * @param $path
     * @param $files
     * @param bool $binary Whether this should be saved with the 'b' flag
     */
    protected function saveFile($path, $file, $fileName, $binary = true)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // use binary 'b' mode, needed for Windows.
        $mode = ($binary) ? 'w+b' : 'w+';
        $fp = fopen($path . '/' . $fileName, $mode);
        fwrite($fp, $file);
        fclose($fp);

        return $path . '/' . $fileName;
    }


    /**
     * Returns all the folders for the given mailbox
     * @param bool $currentFolder Whether the folders should be retrieved from the current folder or entire mailbox
     * @return array an array of <a href="Folder.html">Folder</a> objects.
     */
    public function getAllFolders($currentFolder = false)
    {
        // Exclude any mailboxes (e.g. INBOX) from the mailbox name so we can get all folders
        if (!$currentFolder) {
            preg_match("/\{(.*?)\}/", $this->mailboxName, $mailbox);
            $mailbox = $mailbox[0];
        } else {
            $mailbox = $this->mailboxName;
        }

        $imapFolders = $this->getMailboxFolders($mailbox);
        $folders = new FolderCollection();
        foreach ($imapFolders as $folder) {
            $folders->add(Folder::create($folder));
        }

        return $folders;
    }


    /**
     * Returns the number of messages in the mailbox
     * @return int
     */
    public function getMessageCount()
    {
        return imap_num_msg($this->connection);
    }

    /**
     * Get the full mailbox name
     * @return string
     */
    public function getMailboxName()
    {
        return $this->decodeMailboxName($this->mailboxName);
    }

    /**
     * Gets the current folder name
     * @return string
     */
    public function getFolderName()
    {
        return $this->mailbox->getFolder();
    }

    /**
     * Decodes the mailbox name if it's been encoded with utf-7
     * @param $mailboxName
     * @return mixed|string
     */
    protected function decodeMailboxName($mailboxName)
    {
        // Check for non-printable ascii characters
        if ($this->encoded) {
            $mailboxName = imap_utf7_decode($this->mailboxName);
        }

        return $mailboxName;
    }

    /**
     * Returns the number of unread messages
     * @return int
     */
    public function getUnreadMessageCount()
    {
        $status = imap_status($this->getConnection(), $this->mailboxName, SA_UNSEEN);
        return $status->unseen;
    }

    /**
     * Returns the current imap connection, which can be passed in to php's native imap functions.
     * @return resource
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Returns the Mailbox object
     * @return Mailbox
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     * Deletes all messages from the trash folder
     * @param string $folder
     * @return void
     * @throws Exception
     */
    public function emptyTrash($folder = 'trash')
    {
        $folder = $this->getFolderNameByAlias($folder);
        $this->deleteAllMessages($folder);
    }

    /**
     * Returns the folder name from the config file, or the alias itself if no folder is found.
     * @param string $alias the alias
     * @return string
     */
    protected function getFolderNameByAlias($alias)
    {
        return (isset($this->config['aliases'][$alias])) ? $this->config['aliases'][$alias] : $alias;
    }

    /**
     * Deletes all messages from the given folder
     * @param string $folder The folder name
     * @throws Exception
     * @return bool
     */
    public function deleteAllMessages($folder)
    {
        if ($this->openFolder($folder)) {
            return $this->deleteMessages('1:*');
        }

        throw new Exception("Unable to delete messages. $folder folder does no exist!");
    }

    /**
     * Opens a connection to the given folder
     * @param string $folder The name or alias of the folder to open
     * @return bool Returns true on success, false on failure
     */
    public function openFolder($folder)
    {
        // Check for alias in config first or use folder as string.
        $folder = $this->getFolderNameByAlias($folder);

        $this->mailbox->setFolder($folder);
        $this->mailboxName = $this->createImapMailbox($this->mailbox);

        return $this->refresh();
    }

    /**
     * Resets/Refreshes the connection to the mail server
     * @return bool
     */
    public function refresh()
    {
        return imap_reopen($this->connection, $this->mailboxName);
    }

    /**
     * Deletes the given message from the mailbox
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @return bool
     */
    public function deleteMessages($messageList)
    {
        if (imap_delete($this->connection, $messageList)) {
            return imap_expunge($this->connection);
        }

        return false;
    }

    /**
     * Moves the given messages to the trash folder
     * @param string $messageList A comma delimited list of message numbers (see: <a href="#method_getMessageList">getMessageList()</a>)
     * @param string $folder The name of the trash folder or it's alias
     * @return void
     */
    public function moveToTrash($messageList, $folder = 'trash')
    {
        $folder = $this->getFolderNameByAlias($folder);
        imap_mail_move($this->connection, $messageList, $folder);
        imap_expunge($this->connection);
    }

    /**
     * Closes the connection to the mail server
     * @param bool $deleteFlaggedEmails If set to true all emails flagged for deletion will be removed
     * @return bool
     */
    public function closeConnection($deleteFlaggedEmails = true)
    {
        if ($deleteFlaggedEmails) {
            return imap_close($this->connection, CL_EXPUNGE);
        }

        return imap_close($this->connection);
    }

    /**
     * Sets the disposition params (essentially the filename of the attachments which haven't been embedded)
     * @param $part
     * @param $bodyPart
     * @return void
     */
    private function setDispositionParams($part, &$bodyPart)
    {
        // Now lets look for disposition parameters for attachments
        if ($part->ifdparameters) {
            if ($part->disposition === "ATTACHMENT") {
                foreach ($part->dparameters as $param) {
                    if ($param->attribute == 'FILENAME') {
                        $bodyPart->setName($param->value);
                    }
                }
            }
        }
    }

    /**
     * Encodes the body to the set encoding (by default UTF-8)
     * @param $section
     * @param $body
     * @return mixed|string
     */
    private function encode($section, $body)
    {
        $charset = ($section->getCharset()) ? $section->getCharset() : mb_detect_encoding($body);
        if ($charset) {
            return mb_convert_encoding($body, $this->outputEncoding, $charset);
        } elseif ($this->outputEncoding == "UTF-8") {
            return utf8_encode($body);
        }

        return mb_convert_encoding($body, $this->outputEncoding);
    }

    /**
     * Sets the output encoding to the given encoding. By default the output encoding is
     * UTF-8, so this is only required when you want your output to use a different encoding.
     * @param string $encoding The encoding (see: <a href="http://php.net/manual/en/mbstring.supported-encodings.php">http://php.net/manual/en/mbstring.supported-encodings.php</a>)
     * @return void
     */
    public function setOutputEncoding($encoding)
    {
        $this->outputEncoding = $encoding;
    }

    /**
     * Returns the message headers
     * @param $messageNo
     * @return object
     */
    public function getMessageHeaders($messageNo)
    {
        return imap_headerinfo($this->connection, $messageNo);
    }

    /**
     * Returns the sorted results
     * @param $criteria
     * @param $sortBy
     * @param $reverse
     * @return array
     */
    public function sort($criteria, $sortBy, $reverse, $options = 0)
    {
        return imap_sort($this->connection, $sortBy, $reverse, $options, $criteria);
    }

    /**
     * Builds the search string for imap_sort search criteria.
     * @param $criteria
     * @param $searchString
     * @return string
     */
    private function buildSearchString($criteria)
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
        return $searchString;
    }

    /**
     * @param $mailbox
     * @return array
     */
    public function getMailboxFolders($mailbox, $pattern = '*')
    {
        $imapFolders = imap_getmailboxes($this->connection, $mailbox, $pattern);
        return $imapFolders;
    }

    /**
     * Get the message number for the uid.
     * @param $uid
     * @return int
     */
    public function getMessageNumber($uid)
    {
        return imap_msgno($this->connection, $uid);
    }

    /**
     * Returns the email id to match against a cid in an email body
     * @param $section
     * @return string
     */
    private function getCid($section)
    {
        return preg_quote(preg_replace(['/^</', '/>$/'], '', $section->getId()));
    }

    /**
     * Returns true if there is a cid (id) AND it's cid reference can be found in the email.
     * @param $id
     * @param $body
     * @return bool
     */
    private function cidFoundInEmailBody($cid, $body)
    {
        return $cid && preg_match("/cid:\s?$cid/", $body);
    }

    /**
     * @param $section
     * @param $messageNo
     * @return string
     */
    private function getEmbeddedImage($section, $messageNo)
    {
        return $this->decode($section->getEncoding(), $this->fetchBody($messageNo, $section->getSection()));
    }

    /**
     * @param $section
     * @param $messageNo
     * @param $path
     * @return string
     */
    private function downloadEmbeddedImage($section, $messageNo)
    {
        $path = (isset($this->config['embedded_image_path'])) ? $this->config['embedded_image_path'] . '/' : '';

        $image = $this->getEmbeddedImage($section, $messageNo);
        $downloadPath = $path . $this->getFolderName() . '/' . $messageNo;
        $file = $downloadPath . '/' . $section->getName();

        if (!file_exists($file)) {

            $file = $this->saveFile($downloadPath, $image, $section->getName(), false);
            return $file;


        }
        return $file;
    }

    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }
}