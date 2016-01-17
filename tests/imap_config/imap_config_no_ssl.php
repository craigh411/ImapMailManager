<?php
/**
 * Change these to the settings for your imap test account and
 * change filename to 'config.php' to run the tests.
 */
return [
    'server' => 'imap.example.com',
    'username' => 'USERNAME',
    'password' => 'PASSWORD',
    'port' => 143,
    'default_folder' => 'INBOX',
    'ssl' => false,
    'validate_cert' => true,
    'aliases' => [
        'inbox' => 'INBOX',
        'trash' => 'INBOX.Trash',
        'spam' => 'INBOX.Spam'
    ]
];


