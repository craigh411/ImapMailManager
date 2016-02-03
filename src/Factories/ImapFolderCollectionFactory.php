<?php


namespace Humps\MailManager\Factories;


use Humps\MailManager\Collections\FolderCollection;
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
        if(count($folders)) {
            foreach ($folders as $folder) {
                static::addFolder($collection, $folder);
            }
        }
        return $collection;
    }

    /**
	 * Adds a folder for the folder collection
     * @param $folders
     * @param $folder
     */
    protected static function addFolder(FolderCollection $folders, $folder)
    {
        $folders->add(Folder::create($folder));
    }


}