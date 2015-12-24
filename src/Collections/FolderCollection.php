<?php


namespace Humps\MailManager\Collections;

use Humps\MailManager\Folder;

class FolderCollection extends Collection
{
    function __construct()
    {
        parent::__construct();
    }

    public function add(Folder $folder, $key = null)
    {
        parent::addCollectable($folder, $key);
    }

}