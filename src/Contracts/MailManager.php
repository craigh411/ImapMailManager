<?php

namespace Humps\MailManager\Contracts;

use Exception;

interface MailManager
{
    /**
     * Returns the current connection
     * @return bool|false|resource
     */
    public function getConnection();

    /**
     * Returns all the folder names for the given mailbox
     * @return array
     */
    public function getAllFolders($currentFolder);

    /**
     * @param $folder
     */
    public function openFolder($folder);

    /**
     * Gets all messages for the given sender
     * @param $emails
     * @return array
     */
    public function getMessagesBySender($sender);

    /**
     * Search for a message by the given criteria
     * @param $criteria
     * @param null $search
     * @return array
     */
    public function searchMessages($criteria, $searches = null, $sort = SORTDATE, $reverse = true);

    /**
     * Get Message by message Number
     * @param $messageNo
     */
    public function getMessage($messageNo, $options);

    /**
     * Get message by uid
     * @param $uid
     * @return Message
     */
    public function getMessageByUid($uid);

    /**
     * Returns all message details for the given mailbox
     * @return array
     */
    public function getAllMessages();

    /**
     * Returns the name of the mailbox
     * @return string
     */
    public function getMailboxName();

    public function getMessageCount();

    public function deleteMessages($messageList);

    /**
     * Deletes all messages from the given folder
     * @param string $folder
     */
    public function deleteAllMessages($folder);

    /**
     * Deletes the messages from the trash folder
     */
    public function emptyTrash($folder = 'trash');

    public function moveToTrash($messageList, $folder = 'trash');

    /**
     * Resets the connection to the mailserver
     */
    public function refresh();

    /**
     * Closes the connection to the mail server
     */
    public function closeConnection();

    /**
     * Returns the message numbers for the given messages
     * @param array $messages - Expects an array of Message objects
     * @return array
     * @throws Exception
     */
    public static function getMessageNumbers(array $messages);


}