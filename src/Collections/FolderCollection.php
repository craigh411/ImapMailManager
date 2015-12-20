<?php


namespace Humps\MailManager\Collections;

use Humps\MailManager\Folder;

class FolderCollection extends Collection
{
    function __construct()
    {
        parent::__construct();
    }

    public function addFolder(Folder $folder, $key = null)
    {
        return parent::add($folder);
    }

}