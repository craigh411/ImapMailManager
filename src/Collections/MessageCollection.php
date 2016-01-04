<?php


namespace Humps\MailManager\Collections;


use Humps\MailManager\Contracts\Message;
use InvalidArgumentException;

abstract class MessageCollection extends AbstractCollection
{
    function __construct()
    {
        parent::__construct();
    }

    public function add(Collectable $message, $key = null)
    {
        if ($message instanceof Message) {
            parent::add($message, $key);
        } else {
            throw new InvalidArgumentException('Message object expected');
        }
    }
}