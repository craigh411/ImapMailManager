<?php


namespace Humps\MailManager\Collections;


use Humps\MailManager\Contracts\Message;
use Humps\MailManager\ImapMessage;
use InvalidArgumentException;

class ImapMessageCollection extends MessageCollection
{
    function __construct()
    {
        parent::__construct();
    }

    public function add(Collectable $message, $key = null)
    {
        if ($message instanceof ImapMessage) {
            parent::add($message, $key);
        } else {
            throw new InvalidArgumentException('ImapMessage object expected');
        }
    }

    public function jsonSerialize()
    {
        return [
            'messages' => $this->collection
        ];
    }
}