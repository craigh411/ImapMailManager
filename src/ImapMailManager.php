<?php


namespace Humps\MailManager;

use Carbon\Carbon;
use Exception;
use Humps\MailManager\Contracts\MailManager;
use Humps\MailManager\Contracts\Message;

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
     * @param string $config
     * @throws Exception
     */
    public function __construct($folder = 'INBOX', $config = 'imap_config.php')
    {
        try {
            $this->mailbox = MailboxFactory::create($config);
            $this->mailbox->setFolder($folder);
            $this->mailboxName = $this->createImapMailbox($this->mailbox);
            $this->connection = $this->connect();
            $this->loadConfig($config);

            $this->encoded = false;
            $this->outputEncoding = 'UTF-8';
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * How the body should be encoded, by default this is utf-8
     * @param $encoding
     */
    public function setOutputEncoding($encoding)
    {
        $this->outputEncoding = $encoding;
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
     * Encodes the mailbox name
     * @param $mailboxName
     * @return mixed|string
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
     * Open the connection to the mailbox
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
    public function loadConfig($config)
    {
        if (file_exists($config)) {
            $this->config = include $config;
        } else {
            $this->config = [];
        }
    }

    /**
     * Returning a comma delimited message list
     * @param array $messages
     * @return string
     * @throws Exception
     */
    public static function getMessageList(array $messages)
    {
        return implode(',', self::getMessageNumbers($messages));
    }

    /**
     * Returns the message numbers for the given messages
     * @param array $messages - Expects an array of Message objects
     * @return array
     * @throws Exception
     */
    public static function getMessageNumbers(array $messages)
    {
        $messageNos = [];
        if (count($messages)) {
            foreach ($messages as $message) {
                if ($message instanceof Message) {
                    $messageNos[] = $message->getMessageNo();
                } else {
                    throw new Exception('array of Message objects expected. ' . get_class($message) . ' Received');
                }
            }
        }
        return $messageNos;
    }


    /**
     * Flags the given messages as read
     * @param $messageList
     * @return bool
     */
    public function flagAsRead($messageList)
    {
        return $this->setFlag($messageList, '\Seen');
    }

    /**
     * Sets the given flag on the given messages
     * @param $messageList
     * @param $flag - \Seen, \Answered, \Flagged, \Deleted, and \Draft
     * @return bool
     */
    public function setFlag($messageList, $flag)
    {
        return imap_setflag_full($this->connection, $messageList, $flag);
    }

    /**
     * Returns all unread messages
     * @param bool|false $markAsRead
     * @return array
     */
    public function getUnreadMessages($markAsRead = false)
    {
        return $this->searchMessages('UNSEEN', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }

    /**
     * Search for a message by the given criteria
     * @param $criteria
     *      What is being searched. see imap_search criteria (http://php.net/manual/en/function.imap-search.php)
     * @param null $searches string | array
     * @return array
     */
    public function searchMessages($criteria, $searches = null, $sort = SORTDATE, $reverse = true, $messageOptions = 0, $headersOnly = false)
    {
        $messages = [];

        // Convert any string searches to an array
        if (!is_array($searches) && $searches) {
            $searches = [$searches];
        }

        $criteria = strtoupper($criteria);

        // Search using the criteria and search terms
        if ($searches) {
            $messageIds = [];
            foreach ($searches as $search) {
                if ($found = imap_sort($this->connection, $sort, $reverse, 0, $criteria . ' "' . $search . '"')) {
                    $messageIds = array_merge($found, $messageIds);
                }
            }
        } else {
            // There are no search terms, so just use the criteria (some criteria such as ALL, do not have searches)
            $messageIds = imap_sort($this->connection, $sort, $reverse, 0, $criteria);
        }

        // Get the details for each message and store in an array
        foreach ($messageIds as $messageId) {
            $messages[] = $this->getMessage($messageId, $messageOptions, $headersOnly);
        }

        return $messages;
    }

    /**
     * Get Message by message Number
     * @param $messageNo
     * @param $options
     * @params $headersOnly
     * @params $downloadPath
     * @return Message $message
     */
    public function getMessage($messageNo, $options = 0, $headersOnly = false)
    {
        $message = new ImapMessage(imap_headerinfo($this->connection, $messageNo));

        if (!$headersOnly) {
            $this->setMessageBody($message);
        }

        $message->setAttachments($this->getAttachments($messageNo));
        $this->getEmbeddedImages($message);

        return $message;
    }

    /**
     * Get the main body of the message
     * @param Message $message
     * @param $options
     * @return string
     */
    private function setMessageBody(Message $message, $options = 0)
    {
        $messageNo = $message->getMessageNo();
        $structure = $this->fetchStructure($messageNo);
        $bodyParts = $this->fetchBodyParts($structure);


        $hasHtmlBody = false;
        $hasTextBody = false;

        if (count($bodyParts)) {
            foreach ($bodyParts as $part) {
                foreach ($part as $i => $section) {
                    if ($section->getSubType() == 'PLAIN') {
                        $hasTextBody = true;
                        $body = $this->decode($section->getEncoding(), $this->fetchBody($messageNo, $section->getSection()));
                        $body = $this->encode($section, $body);
                        $message->setTextBody($body);
                    }

                    if ($section->getSubType() == 'HTML') {
                        $hasHtmlBody = true;
                        $body = $this->decode($section->getEncoding(), $this->fetchBody($messageNo, $section->getSection()));
                        $body = $this->encode($section, $body);
                    }
                    $message->setHtmlBody($body);
                }
            }
        }

        if (!$hasHtmlBody && $hasTextBody) {
            $message->setHtmlBody(nl2br($message->getTextBody()));
        }
    }

    /**
     * Returns all the parts from the body and returns them as an array with the sections of
     * each part. So $parts[0] will contain all part 1 sections and $parts[1] will contain all part 2 sections etc.
     * @param $messageNo
     * @param $options
     */

    /**
     * @param $messageNo
     * @return object
     */
    public
    function fetchStructure($messageNo)
    {
        return imap_fetchstructure($this->connection, $messageNo);
    }

    /**
     * Returns all the parts from the body
     * @param $messageNo
     * @param $options
     */
    public
    function fetchBodyParts($structure, $parts = [], $sections = [], $options = 0)
    {
        $sections[] = 1;
        if (isset($structure->parts) && count($structure->parts)) {
            foreach ($structure->parts as $i => $part) {
                if (isset($part->parts)) {
                    // We have more parts, lets get them
                    $parts = $this->fetchBodyParts($part, $parts, $sections);
                } else {
                    // Make the last element of array the loop number, that's the final section we are in!
                    // e.g. 1.1.2
                    $sections[count($sections) - 1] = $i + 1;
                    // Break array in to the main sections
                    $section = implode(".", $sections);
                    $bodyPart = new ImapBodyPart($part->type, $part->encoding, $part->subtype, $section);
                    $this->setParams($part, $bodyPart, $part->type);
                    $this->setDispositionParams($part, $bodyPart);
                    if (isset($part->id)) {
                        $bodyPart->setId($part->id);
                    }

                    $parts[$sections[0] - 1][] = $bodyPart;
                }
            }
        } elseif (isset($structure) && count($structure)) {
            $parts[0][] = new ImapBodyPart($structure->type, $structure->encoding, $structure->subtype, 1);
        }

        //var_dump($parts);

        return $parts;
    }

    /**
     * @param $part
     * @param $bodyPart
     * @return mixed
     */
    private
    function setParams($part, &$bodyPart, $type)
    {
// Check for charset and embedded files (these will be in parameters)
        if ($part->ifparameters) {
            foreach ($part->parameters as $param) {
                if ($param->attribute == 'CHARSET') {
                    $bodyPart->setCharset($param->value);
                }
                if ($param->attribute == 'NAME') {
                    if ($type == TYPEIMAGE) {
                        $bodyPart->setEmbedded(true);
                    }
                    $bodyPart->setName($param->value);
                }
            }
        }
    }

    /**
     * @param $encoding
     * @param $body
     * @return string
     */
    private
    function decode($encoding, $body)
    {
        switch ($encoding) {
            case ENCBASE64:
                return imap_base64($body);
            case ENCQUOTEDPRINTABLE:
                return imap_qprint($body);
            case ENCBINARY:
                return imap_binary($body);
            default:
                $decoder = new EmailDecoder($body);
                return $decoder->decode();
        }
    }

    /**
     * @param $messageNo
     * @param $part
     * @return string
     */
    public
    function fetchBody($messageNo, $part, $options = 0)
    {
        return imap_fetchbody($this->connection, $messageNo, $part, $options);
    }

    /**
     * Get the E-mail attachment details for the given message number
     * @param $messageNo
     * @return array
     */
    public
    function getAttachments($messageNo)
    {
        $files = [];
        $structure = $this->fetchStructure($messageNo);

        // SEE: http://php.net/manual/en/function.imap-fetchstructure.php
        // FOr what all these attributes are.
        if (isset($structure->parts) && count($structure->parts)) {
            foreach ($structure->parts as $i => $part) {
                if ($part->ifdparameters) {
                    foreach ($part->dparameters as $param) {
                        if ($param->attribute == 'FILENAME') {
                            $files[] = [
                                'filename' => $param->value,
                                'part' => $i + 1, // parts are 1 based, not 0 based.
                                'encoding' => $part->encoding
                            ];
                        }
                    }
                }
            }
        }

        return $files;
    }

    /**
     * Gets the embedded images for the given messages and alters the body accordingly
     * @param Message $message
     */
    public
    function getEmbeddedImages(Message &$message)
    {
        // First get all images
        $messageNo = $message->getMessageNo();
        $structure = $this->fetchStructure($messageNo);
        $bodyParts = $this->fetchBodyParts($structure);

        if (count($bodyParts)) {
            foreach ($bodyParts as $part) {
                foreach ($part as $i => $section) {
                    if ($section->isEmbedded()) {
                        $image = $this->decode($section->getEncoding(), $this->fetchBody($messageNo, $section->getSection()));
                        $file = $this->saveFile($messageNo . '/embedded', $image, $section->getName(), false);

                        // Let's adjust the Email body to point to the image
                        $body = $message->getHtmlBody();
                        $id = $section->getId();
                        // remove any the lt and gt symbols at start and end if they exist.
                        $id = preg_replace(['/^</', '/>$/'], '', $id);
                        $body = preg_replace("/cid:$id/", $file, $body);
                        $message->setHtmlBody($body);
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
    public
    function markAsRead($markAsRead)
    {
        $options = ($markAsRead) ? 0 : FT_PEEK;
        return $options;
    }

    /**
     * Download the attachments for the given message number
     * @param $messageNo
     * @param array $filenames
     * @param string $path
     */
    public
    function downloadAttachments($messageNo, $filenames = [], $path = '')
    {
        $attachments = $this->getAttachments($messageNo);
        $files = [];
        if (count($attachments)) {
            foreach ($attachments as $attachment) {
                $file = $this->fetchBody($messageNo, $attachment['part']);
                $decodedAttachment = $this->decode($attachment['encoding'], $file);
                $files[] = [
                    'decodedFile' => $decodedAttachment,
                    'filename' => $attachment['filename']
                ];

                if (in_array($attachment['filename'], $filenames) || empty($filenames)) {
                    $binary = ($attachment['encoding'] == ENCBINARY) ? true : false;
                    $this->saveFile($messageNo, $path, $files, $binary);
                }
            }
        }
    }

    /**
     * Save the file to the given path
     * @param $messageNo
     * @param $path
     * @param $files
     */
    protected
    function saveFile($path, $file, $fileName, $binary = false)
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
     * Returns all unread messages
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getReadMessages($markAsRead = false)
    {
        return $this->searchMessages('SEEN', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }

    /**
     * Flags the given messages as important
     * @param $messageList
     * @return bool
     */
    public
    function flagAsImportant($messageList)
    {
        return $this->setFlag($messageList, '\Flagged');
    }

    /**
     * Returns all flagged messages
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getImportantMessages($markAsRead = false)
    {
        return $this->searchMessages('FLAGGED', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }

    /**
     * Flags the given messages as important
     * @param $messageList
     * @return bool
     */
    public
    function flagAsAnswered($messageList)
    {
        return $this->setFlag($messageList, '\Answered');
    }

    /**
     * Returns all unanswered messages
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getAnsweredMessages($markAsRead = false)
    {
        return $this->searchMessages('Answered', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }

    /**
     * Returns all unanswered messages
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getUnansweredMessages($markAsRead = false)
    {
        return $this->searchMessages('Unanswered', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }

    /**
     * Returns all the folder names for the given mailbox
     *
     * @param $excludeMailbox
     * @return array
     */
    public
    function getAllFolders($excludeMailbox = true)
    {
        // Exclude any mailboxes (e.g. INBOX) from the mailbox name so we can get all folders
        if ($excludeMailbox) {
            preg_match("/\{(.*?)\}/", $this->mailboxName, $mailbox);
            $mailbox = $mailbox[0];
        } else {
            $mailbox = $this->mailbox;
        }

        $imapFolders = imap_getmailboxes($this->connection, $mailbox, '*');
        $folders = [];
        foreach ($imapFolders as $folder) {
            $folders[] = new Folder($folder);
        }

        return $folders;
    }

    /**
     * Gets all messages for the given sender
     * @param $sender
     * @param int $sort
     * @param bool $reverse
     * @param bool $markAsRead
     * @return array
     */
    public
    function getMessagesBySender($sender, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        return $this->searchMessages('from', $sender, $sort, $reverse, $this->markAsRead($markAsRead));
    }

    /**
     * @param $subject
     * @param int $sort
     * @param bool|true $reverse
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getMessagesBySubject($subject, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        return $this->searchMessages('subject', $subject, $sort, $reverse, $this->markAsRead($markAsRead));
    }

    /**
     * @param $cc
     * @param int $sort
     * @param bool|true $reverse
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getMessagesByCC($cc, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        return $this->searchMessages('cc', $cc, $sort, $reverse, $this->markAsRead($markAsRead));
    }

    /**
     * @param $bcc
     * @param int $sort
     * @param bool|true $reverse
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getMessagesByBCC($bcc, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        return $this->searchMessages('bcc', $bcc, $sort, $reverse, $this->markAsRead($markAsRead));
    }

    /**
     * @param $to
     * @param int $sort
     * @param bool|true $reverse
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getMessagesByReceiver($to, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        return $this->searchMessages('to', $to, $sort, $reverse, $this->markAsRead($markAsRead));
    }

    /**
     * @param $date
     * @param int $sort
     * @param bool|true $reverse
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getMessagesByDate($date, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        return $this->searchMessages('on', $date, $sort, $reverse, $this->markAsRead($markAsRead));
    }

    /**
     * @param $date
     * @param int $sort
     * @param bool|true $reverse
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getMessagesBefore($date, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        return $this->searchMessages('before', $date, $sort, $reverse, $this->markAsRead($markAsRead));
    }

    /**
     * Gets messages sent between the two dates
     * @param $from
     * @param $to
     * @return array
     * @throws Exception
     */
    public
    function getMessagesBetween($from, $to, $sort = SORTDATE, $reverse = false)
    {
        $fromTS = Carbon::parse($from)->timestamp;
        $to = Carbon::parse($to)->addDays(1)->timestamp;

        if ($fromTS > $to) {
            throw new Exception('Invalid Date Range');
        }

        // Get all messages before the upper date range
        $messages = $this->getMessagesAfter($from, $sort, $reverse, false);
        $filtered = [];
        foreach ($messages as $message) {
            $date = $message->getDate();
            echo $date->timestamp;
            if ($date->timestamp <= $to) {
                $filtered[] = $message;
            }
        }

        return $filtered;
    }

    /**
     * @param $date
     * @param int $sort
     * @param bool|true $reverse
     * @param bool|false $markAsRead
     * @return array
     */
    public
    function getMessagesAfter($date, $headersOnly = false, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        return $this->searchMessages('since', $date, $sort, $reverse, $this->markAsRead($markAsRead), $headersOnly);
    }

    /**
     * Get message by uid
     * @param $uid
     * @return ImapMessage
     */
    public
    function getMessageByUid($uid)
    {
        return $this->getMessage(imap_msgno($this->connection, $uid));
    }

    public
    function getEncoding($messageNo)
    {
        return $this->fetchStructure($messageNo)->encoding;

    }

    /**
     * Returns all message details for the given mailbox
     * @param bool $markAsRead
     * @return array
     */
    public
    function getAllMessages($markAsRead = false)
    {
        return $this->sort(SORTDATE, true, $markAsRead);
    }

    /**
     * Sorts the mail by the given criteria
     * @param $criteria
     * @param bool|false $reverse
     * @return array
     */
    public
    function sort($criteria, $reverse = false, $markAsRead = false)
    {
        $options = $this->markAsRead($markAsRead);
        return $this->searchMessages('ALL', null, $criteria, $reverse, $options);
    }

    /**
     * Returns the number of messages in the mailbox
     * @return int
     */
    public
    function getMessageCount()
    {
        return imap_num_msg($this->connection);
    }

    /**
     * Returns the mail server
     * @return string
     */
    public function getMailboxName()
    {
        return $this->decodeMailboxName($this->mailboxName);
    }

    /**
     * Gets the current folder name
     * Returns the mail server
     * @return string
     */
    public function getFolderName()
    {
        return $this->mailbox->getFolder();
    }

    /**
     * Decodes the mailbox name
     * @param $mailboxName
     * @return mixed|string
     */
    private
    function decodeMailboxName($mailboxName)
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
    public
    function getUnreadMessageCount()
    {
        $status = imap_status($this->getConnection(), $this->mailboxName, SA_UNSEEN);
        return $status->unseen;
    }

    /**
     * Returns the current imap connection
     * @return bool|false|resource
     */
    public
    function getConnection()
    {
        return $this->connection;
    }

    /**
     * Returns the Mailbox object
     * @return Mailbox
     */
    public
    function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     * Deletes all messages from the trash folder
     * @param string $folder
     * @throws Exception
     */
    public
    function emptyTrash($folder = 'trash')
    {
        $folder = $this->getAliasFromConfig($folder);
        $this->deleteAllMessages($folder);
    }

    /**
     * Returns the alias from the config file, or the folder name is not found.
     * @param $folder
     * @return mixed
     */
    protected
    function getAliasFromConfig($folder)
    {
        return (isset($this->config['aliases'][$folder])) ? $this->config['aliases'][$folder] : $folder;
    }

    /**
     * Deletes all messages from the given folder
     * @param string $folder
     * @throws Exception
     */
    public
    function deleteAllMessages($folder)
    {
        if ($this->openFolder($folder)) {
            return $this->deleteMessages('1:*');
        }

        throw new Exception("Unable to delete messages. $folder folder does no exist!");
    }

    /**
     * Opens a connection to the given folder
     * @param $folder
     * @return bool
     */
    public
    function openFolder($folder)
    {
        // Check for alias in config first or use folder as string.
        $folder = $this->getAliasFromConfig($folder);

        $this->mailbox->setFolder($folder);
        $this->mailboxName = $this->createImapMailbox($this->mailbox);

        return $this->refresh();
    }

    /**
     * Resets/Refreshes the connection to the mail server
     * @return bool
     */
    public
    function refresh()
    {
        return imap_reopen($this->connection, $this->mailboxName);
    }

    /**
     * Deletes the given message from the mailbox
     * @param $messageList
     * @return bool
     */
    public
    function deleteMessages($messageList)
    {
        if (imap_delete($this->connection, $messageList)) {
            return imap_expunge($this->connection);
        }

        return false;
    }

    /**
     * Moves the given messages to the trash folder
     * @param $messageList
     * @param string $folder
     */
    public
    function moveToTrash($messageList, $folder = 'trash')
    {
        $folder = $this->getAliasFromConfig($folder);
        imap_mail_move($this->connection, $messageList, $folder);
        imap_expunge($this->connection);
    }

    /**
     * Closes the connection to the mail server
     * @return bool
     */
    public
    function closeConnection($deleteFlaggedEmails = true)
    {
        if ($deleteFlaggedEmails) {
            return imap_close($this->connection, CL_EXPUNGE);
        }

        return imap_close($this->connection);
    }

    /**
     * @param $part
     * @param $bodyPart
     */
    private
    function setDispositionParams($part, &$bodyPart)
    {
// Now lets look for disposition parameters for attachments
        if ($part->ifdparameters) {
            foreach ($part->dparameters as $param) {
                if ($param->attribute == 'FILENAME') {
                    $bodyPart->setName($param->value);
                }
            }
        }
    }

    /**
     * @param $section
     * @param $body
     * @return mixed|string
     */
    private function encode($section, $body)
    {
        $charset = ($section->getCharset()) ? $section->getCharset() : mb_detect_encoding($body);
        if ($charset) {
            return mb_convert_encoding($body, $this->outputEncoding, $charset);
        }elseif($this->outputEncoding == "UTF-8"){
            return utf8_encode($body);
        }

        return mb_convert_encoding($body, $this->outputEncoding);

    }


}