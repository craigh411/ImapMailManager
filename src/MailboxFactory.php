<?php


namespace Humps\ImapMailManager;


class MailboxFactory
{

    public static function create($configFile = 'imap_config.php')
    {
        $config = include $configFile;

        return new Mailbox(
            $config['server'],
            $config['username'],
            $config['password'],
            $config['port'],
            $config['main_folder'],
            $config['ssl'],
            $config['validate_cert']
        );
    }
}