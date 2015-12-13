<?php


namespace Humps\ImapMailManager;

use Carbon\Carbon;
use Exception;
use Humps\ImapMailManager\Contracts\MailManager;

class ImapMailManager implements MailManager
{

    protected $mailboxName;
    protected $connection;
    protected $config;
    protected $mailbox;

    /**
     * MailManager constructor.
     * @param string $config
     * @throws Exception
     */
    public function __construct($config = 'imap_config.php')
    {
        $this->mailbox = MailboxFactory::create($config);
        $this->mailboxName = $this->createImapMailbox($this->mailbox);

        try {
            $this->connection = $this->connect();
        } catch (Exception $e) {
            throw new Exception('Unable to connect to mailbox ' . $this->mailboxName);
        }

        $this->loadConfig($config);
    }

    /**
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

        // Check for non-printable ascii characters
        if (!mb_check_encoding($mailboxName, 'ISO-8859-1')) {
            $mailboxName = imap_utf7_encode($mailboxName);
        }

        return $mailboxName;
    }

    /**
     * @param Mailbox $mb
     * @return resource
     */
    protected function connect()
    {
        return imap_open($this->mailboxName, $this->mailbox->getUsername(), $this->mailbox->getPassword());
    }

    /**
     * Loads the config array
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
     * Returns the message numbers for the given messages
     * @param array $messages - Expects an array of Message objects
     * @return array
     * @throws Exception
     */
    public static function getMessageNumbers(array $messages)
    {
        $messageNos = [];
        foreach ($messages as $message) {
            if ($message instanceof Message) {
                $messageNos[] = $message->getMessageNo();
            } else {
                throw new Exception('array of Message objects expected. ' . get_class($message) . ' Received');
            }
        }
        return $messageNos;
    }

    /**
     * Convenience method for returning a comma delimited message list
     * @param array $messages
     * @return string
     * @throws Exception
     */
    public static function getMessageList(array $messages)
    {
        return implode(',', self::getMessageNumbers($messages));

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
     * Returns all unread messages
     * @param bool|false $markAsRead
     * @return array
     */
    public function getUnreadMessages($markAsRead = false)
    {
        return $this->searchMessages('UNSEEN', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }

    /**
     * Returns all unread messages
     * @param bool|false $markAsRead
     * @return array
     */
    public function getReadMessages($markAsRead = false)
    {
        return $this->searchMessages('SEEN', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }


    /**
     * Flags the given messages as important
     * @param $messageList
     * @return bool
     */
    public function flagAsImportant($messageList)
    {
        return $this->setFlag($messageList, '\Flagged');
    }

    /**
     * Returns all flagged messages
     * @param bool|false $markAsRead
     * @return array
     */
    public function getImportantMessages($markAsRead = false)
    {
        return $this->searchMessages('FLAGGED', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }

    /**
     * Flags the given messages as important
     * @param $messageList
     * @return bool
     */
    public function flagAsAnswered($messageList)
    {
        return $this->setFlag($messageList, '\Answered');
    }

    /**
     * Returns all unanswered messages
     * @param bool|false $markAsRead
     * @return array
     */
    public function getAnsweredMessages($markAsRead = false)
    {
        return $this->searchMessages('Answered', null, SORTDATE, true, $this->markAsRead($markAsRead));
    }

    /**
     * Returns all unanswered messages
     * @param bool|false $markAsRead
     * @return array
     */
    public function getUnansweredMessages($markAsRead = false)
    {
        return $this->searchMessages('Unanswered', null, SORTDATE, true, $this->markAsRead($markAsRead));
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
     * Returns all the folder names for the given mailbox
     * @return array
     */
    public function getAllFolders($excludeMailbox = true)
    {
        // Exclude any mailboxes from the mailbox name so we can get all folders
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
     * @param $emails
     * @return array
     */
    public function getMessagesBySender($sender, $sort = SORTDATE, $reverse = true, $markAsRead = false)
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
    public function getMessagesBySubject($subject, $sort = SORTDATE, $reverse = true, $markAsRead = false)
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
    public function getMessagesByCC($cc, $sort = SORTDATE, $reverse = true, $markAsRead = false)
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
    public function getMessagesByBCC($bcc, $sort = SORTDATE, $reverse = true, $markAsRead = false)
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
    public function getMessagesByReceiver($to, $sort = SORTDATE, $reverse = true, $markAsRead = false)
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
    public function getMessagesByDate($date, $sort = SORTDATE, $reverse = true, $markAsRead = false)
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
    public function getMessagesSince($date, $sort = SORTDATE, $reverse = true, $markAsRead = false)
    {
        $date = Carbon::parse($date)->format('d-M-Y');
        return $this->searchMessages('since', $date, $sort, $reverse, $this->markAsRead($markAsRead));
    }

    /**
     * @param $date
     * @param int $sort
     * @param bool|true $reverse
     * @param bool|false $markAsRead
     * @return array
     */
    public function getMessagesBefore($date, $sort = SORTDATE, $reverse = true, $markAsRead = false)
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
    public function getMessagesBetween($from, $to, $sort = SORTDATE, $reverse = true)
    {
        $from = Carbon::parse($from)->timestamp;
        $to = Carbon::parse($to)->timestamp;

        if ($from > $to) {
            throw new Exception('Invalid Date Range');
        }

        // Get all messages before the upper date range
        $messages = $this->getMessagesBefore($to, $sort, $reverse, false);
        $filtered = [];
        foreach ($messages as $message) {
            $date = $message->getDate();
            if ($date->timestamp <= $from) {
                $filtered[] = $message;
            }
        }

        return $filtered;
    }

    /**
     * Search for a message by the given criteria
     * @param $criteria
     * @param null $search
     * @return array
     */
    public function searchMessages($criteria, $searches = null, $sort = SORTDATE, $reverse = true, $messageOptions = 0)
    {
        $messages = [];


        if (!is_array($searches) && $searches) {
            $searches = [$searches];
        }

        $criteria = strtoupper($criteria);

        if ($searches) {
            $messageIds = [];
            foreach ($searches as $search) {
                if ($found = imap_sort($this->connection, $sort, $reverse, 0, $criteria . ' "' . $search . '"')) {
                    $messageIds = array_merge($found, $messageIds);
                }
            }
        } else {

            $messageIds = imap_sort($this->connection, $sort, $reverse, 0, $criteria);
        }


        foreach ($messageIds as $messageId) {
            $messages[] = $this->getMessage($messageId, $messageOptions);
        }

        return $messages;
    }

    /**
     * Get message by uid
     * @param $uid
     * @return Message
     */
    public function getMessageByUid($uid)
    {
        return $this->getMessage(imap_msgno($this->connection, $uid));
    }

    /**
     * Get Message by message Number
     * @param $messageNo
     */
    public function getMessage($messageNo, $options = 0, $downloadAttachments = false, $downloadPath = '/')
    {
        $message = new Message(imap_headerinfo($this->connection, $messageNo));
        $body = imap_fetchbody($this->connection, $messageNo, 1.2, $options);
        if (strlen($body) <= 0) {
            $body = quoted_printable_decode(imap_fetchbody($this->connection, $messageNo, 1, $options));
        }
        $message->setBody($body);

        //if ($downloadAttachments) {
        //$attachments = $this->downloadAttachments($messageNo, $downloadPath);
        $attachments = $this->getAttachments($messageNo);

        $this->downloadAttachments($messageNo);
        $message->setAttachments($this->getAttachments($messageNo));
        //}

        return $message;
    }


    public function getAttachments($messageNo)
    {
        $files = [];
        $structure = imap_fetchstructure($this->connection, $messageNo);

        // SEE: http://php.net/manual/en/function.imap-fetchstructure.php
        // FOr what all these attributes are.
        if (isset($structure->parts) && count($structure->parts)) {
            foreach ($structure->parts as $i => $part) {
                if ($part->ifdparameters) {
                    foreach ($part->dparameters as $param) {
                        if ($param->attribute == 'FILENAME') {
                            $files[] = [
                                'filename' => $param->value,
                                'part' => $i + 1, // parts are not 1 based, not 0 based.
                                'encoding' => $part->encoding
                            ];

                        }
                    }
                }
            }
        }

        return $files;
    }


    public function downloadAttachments($messageNo, $path = '')
    {
        $attachments = $this->getAttachments($messageNo);

        $files = [];

        if (count($attachments)) {
            foreach ($attachments as $attachment) {
                if (count($attachments)) {
                    $file = imap_fetchbody($this->connection, $messageNo, $attachment['part']);
                    $encodedFile = ($attachment['encoding'] == ENCBASE64) ? base64_decode($file) : quoted_printable_decode($file);
                    $files[] = [
                        'encodedFile' => $encodedFile,
                        'filename' => $attachment['filename']
                    ];
                }
            }
            $this->saveFile($messageNo, $path, $files);
        }
    }

    /**
     * Returns all message details for the given mailbox
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
    public
    function getMailboxName()
    {
        return $this->mailboxName;
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
     * Deletes the given message from the mailbox
     * @param $messageNo
     * @return bool
     */
    public
    function deleteMessage($messageNo)
    {
        if (imap_delete($this->connection, $messageNo)) {
            return imap_expunge($this->connection);
        }

        return false;
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
        return (isset($this->config['alias'][$folder])) ? $this->config['alias'][$folder] : $folder;
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
            if (imap_delete($this->connection, '1:*')) {
                return imap_expunge($this->connection);
            }
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

        return imap_reopen($this->connection, $this->mailboxName);
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
     * Resets the connection to the mail server
     * @return bool
     */
    public
    function refresh()
    {
        return imap_reopen($this->connection, $this->mailboxName);
    }

    /**
     * Closes the connection to the mail server
     * @return bool
     */
    public
    function closeConnection()
    {
        return imap_close($this->connection);
    }

    /**
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
     * @param $messageNo
     * @param $path
     * @param $files
     */
    protected function saveFile($messageNo, $path, $files)
    {
        foreach ($files as $file) {
            $fp = fopen($path . $messageNo . "_" . $file['filename'], "w+");
            fwrite($fp, $file['encodedFile']);
            fclose($fp);
        }
    }

}