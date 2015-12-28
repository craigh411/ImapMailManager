<?php


namespace Humps\MailManager\Collections;


use Humps\MailManager\Folder;
use InvalidArgumentException;

class FolderCollection extends AbstractCollection
{
    function __construct()
    {
        parent::__construct();
    }

    public function add(Collectable $folder, $key = null)
    {
        if ($folder instanceof Folder) {
            parent::add($folder, $key);
        } else {
            throw new InvalidArgumentException('Folder object expected');
        }
    }

    public function jsonSerialize()
    {
        return [
            'folders' => $this->collection
        ];
    }
}