<?php
/**
 * Config for ImapMailManager. This is used by MailboxFactory::create() to create a Mailbox instance.
 * This should be placed in the folder where you are using ImapMailManager or you can pass a custom
 * location to MailboxFactory::create($configFile). In general you will only need to adjust the
 * server, username and password, the remaining setting have been set to standard defaults.
 */
return [
    'server' => 'imap.example.com',
    'username' => 'USERNAME',
    'password' => 'PASSWORD',
    'port' => 993,
    'main_folder' => 'INBOX',
    'ssl' => true,
    'validate_cert' => true,
    'alias' => [
        'inbox' => 'INBOX',
        'trash' => 'INBOX.Trash',
        'spam' => 'INBOX.Spam'
    ]
];
