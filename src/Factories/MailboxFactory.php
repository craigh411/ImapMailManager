<?php


namespace Humps\MailManager\Factories;


use Exception;
use Humps\MailManager\Components\Mailbox;


class MailboxFactory
{

    /**
     * Creates a Mailbox object from the given config file
     * @param string $configFile
     * @return Mailbox
     * @throws Exception
     */
    public static function create($folder = 'INBOX', $configFile = 'imap_config/config.php')
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
            $folder,
            $config['ssl'],
            $config['validate_cert']
        );
    }
}