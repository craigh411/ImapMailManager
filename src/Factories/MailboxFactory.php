<?php


namespace Humps\MailManager\Factories;


use Exception;
use Humps\MailManager\Mailbox;

class MailboxFactory
{

    public static function create($configFile = 'imap_config.php')
    {
        if(!file_exists($configFile)){
            throw new Exception('Unable to find config file '. $configFile);
        }

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