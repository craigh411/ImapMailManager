<?php


namespace Humps\MailManager\Collections;


use Humps\MailManager\Collections\Contracts\Collectable;
use Humps\MailManager\Components\Folder;
use InvalidArgumentException;

/**
 * A collection of Folder objects
 *
 * @package Humps\MailManager\Collections
 */
class FolderCollection extends AbstractCollection
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds a Folder object to the collection
     * @param Collectable $folder
     * @param string $key
     * @throws InvalidArgumentException
     * @return void
     */
    public function add(Collectable $folder, $key = null)
    {
        if ($folder instanceof Folder) {
            parent::add($folder, $key);
        } else {
            throw new InvalidArgumentException('Folder object expected');
        }
    }
}