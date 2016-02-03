<?php


namespace Humps\MailManager\Tests\Helpers;


use Humps\MailManager\Components\Mailbox;
use Humps\MailManager\Factories\ImapFactory;
use Mockery as m;

class ImapFactoryHelper extends ImapFactory
{



    protected static function getMailbox($folder, $config)
    {
        $mailbox = m::mock('Humps\MailManager\Components\Mailbox');
        $mailbox->shouldReceive('setFolder')->with($folder);
        $mailbox->shouldReceive('getMailboxName')->andReturn('{imap.example.com:993/ssl}'.$folder);
        $mailbox->shouldReceive('getUsername')->andReturn('USERNAME');
        $mailbox->shouldReceive('getPassword')->andReturn('PASSWORD');
        $mailbox->shouldReceive('getPort')->andReturn('993');


        return $mailbox;
    }

    protected static function getConnection(Mailbox $mailbox)
    {
        $connection = m::mock('Humps\MailManager\ImapConnection');
        return $connection;
    }
}