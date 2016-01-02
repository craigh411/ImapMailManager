<?php
/**
 * Change these to the settings for your imap test account and
 * change filename to 'config.php' to run the tests.
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


