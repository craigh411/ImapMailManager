<?php


namespace Humps\MailManager\Collections;


use Humps\MailManager\ImapMessage;

class ImapMessageCollection extends Collection
{
    function __construct()
    {
        parent::__construct();
    }

    public function add(ImapMessage $message, $key = null)
    {
        parent::addCollectable($message, $key);
    }

}