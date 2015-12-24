<?php


namespace Humps\MailManager\Collections;


use Humps\MailManager\Attachment;

class AttachmentCollection extends Collection
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds an Attachment to the collection
     * @param Attachment $attachment
     */
    public function add(Attachment $attachment)
    {
        parent::addCollectable($attachment);
    }
}