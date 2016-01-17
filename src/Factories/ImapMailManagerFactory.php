<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\Config;
use Humps\MailManager\ImapMailManager;

class ImapMailManagerFactory
{
    /**
     * Factory method for creating an ImapMailManager object from the given config
     * @param string $configPath
     * @return ImapMailManager
     */
    public static function create($folder = null, $configPath = 'imap_config/config.php')
    {
        $config = static::getConfig($configPath);
        $folder = $config->getFolderNameByAlias($folder);

        $imap = static::getImap($folder, $configPath);

        return new ImapMailManager($imap, $config);
    }

    /**
     * Gets the config object
     * @param $configPath
     * @return Config
     */
    protected static function getConfig($configPath)
    {
        return new Config($configPath);
    }

    /**
     * Gets the Imap Object
     * @param $folder
     * @param $configPath
     * @return \Humps\MailManager\ImapHandler
     */
    protected static function getImap($folder, $configPath)
    {
        return ImapFactory::create($folder, $configPath);
    }
}