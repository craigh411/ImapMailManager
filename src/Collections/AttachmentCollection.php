<?php


namespace Humps\MailManager\Collections;


use Humps\MailManager\Attachment;
use InvalidArgumentException;

class AttachmentCollection extends AbstractCollection
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds an Attachment to the collection
     * @param Attachment $attachment
     */
    public function add(Collectable $attachment, $key = null)
    {
        if ($attachment instanceof Attachment) {
            parent::add($attachment, $key);
        } else {
            throw new InvalidArgumentException('Attachment object expected');
        }
    }

    /**
     * How this collection will be serialized to json
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'attachments' => $this->collection
        ];
    }
}