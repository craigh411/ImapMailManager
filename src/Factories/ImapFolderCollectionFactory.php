<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\Collections\FolderCollection;
use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\Components\Folder;

class ImapFolderCollectionFactory
{
    /**
     * Creates a collection of folders
     * @param $folders
     * @return FolderCollection
     */
    public static function create($folders)
    {
        $collection = new FolderCollection();
        foreach ($folders as $folder) {
            static::addFolder($collection, $folder);
        }

        return $collection;
    }

    /**
     * @param $folders
     * @param $folder
     */
    protected static function addFolder($folders, $folder)
    {
        $folders->add(Folder::create($folder));
    }


}