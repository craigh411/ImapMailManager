<?php


namespace Humps\MailManager\Collections;


use Humps\MailManager\Components\ImapAttachment;
use Humps\MailManager\Collections\Contracts\Collectable;
use InvalidArgumentException;

/**
 * A collection of Attachment objects
 *
 * @package Humps\MailManager\Collections
 */
class ImapAttachmentCollection extends AbstractCollection
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds an Attachment object to the collection
     * @param Collectable $attachment
     * @param string $key
     * @throws InvalidArgumentException
     * @return void
     */
    public function add(Collectable $attachment, $key = null)
    {
        if ($attachment instanceof ImapAttachment) {
            parent::add($attachment, $key);
        } else {
            throw new InvalidArgumentException('Attachment object expected');
        }
    }
}