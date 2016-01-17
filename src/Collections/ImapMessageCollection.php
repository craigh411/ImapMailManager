<?php


namespace Humps\MailManager\Collections;

use Humps\MailManager\Collections\Contracts\Collectable;
use Humps\MailManager\Components\ImapMessage;
use InvalidArgumentException;

class ImapMessageCollection extends AbstractCollection
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds an ImapMessage object to the collection
     * @param Collectable $message
     * @param string $key
     * @throws InvalidArgumentException
     * @return void
     */
    public function add(Collectable $message, $key = null)
    {
        if ($message instanceof ImapMessage) {
            parent::add($message, $key);
        } else {
            throw new InvalidArgumentException('ImapMessage object expected');
        }
    }
}