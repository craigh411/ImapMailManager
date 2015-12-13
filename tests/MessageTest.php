<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 13/12/15
 * Time: 14:50
 */

namespace Humps\ImapMailManager\Tests;


use Carbon\Carbon;
use Humps\ImapMailManager\ImapMailManager;

class MessageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ImapMailManager
     */
    protected $mailManager;

    public function setup()
    {
        $this->mailManager = new ImapMailManager();

    }

    /**
     * @test
     */
    public function it_gets_email_details()
    {
       // $this->createEmail();
        $this->mailManager->refresh();
        $m = $this->mailManager->getMessage(1);

        // All assertions in 1 test to prevent multiple connections
        $this->assertEquals('TEST MESSAGE', $m->getSubject());
        $this->assertEquals('Testing Content', $m->getBody());
        $this->assertInstanceOf('Carbon\Carbon', $m->getDate());
        $this->assertEquals(Carbon::now()->toDateString(), $m->getDate()->toDateString());
        $this->assertInternalType('string', $m->getRawDate());
        $this->assertInternalType('string', $m->getHeaderDate());
        $this->assertInternalType('string', $m->getSize());
        $this->assertInternalType('array', $m->getMessage());
        $this->assertEquals('foo@test.com', $m->getTo(true));

        $this->assertEquals('foo@test.com', $m->getTo()[0]['email']);
        $this->assertEquals('foo', $m->getTo()[0]['mailbox']);
        $this->assertEquals('test.com', $m->getTo()[0]['host']);

        $this->assertEquals('bar@test.com', $m->getFrom());
        $this->assertEquals('bar@test.com', $m->getFrom(false)[0]['email']);
        $this->assertEquals('bar', $m->getFrom(false)[0]['mailbox']);
        $this->assertEquals('test.com', $m->getFrom(false)[0]['host']);

        $m->setBody('foo bar baz');
        $this->assertEquals('foo bar baz', $m->getBody('foo bar baz'));

        $this->assertNull($m->getCC());
    }

    public function it_downloads_an_attachment()
    {

    }

    private function createEmail()
    {
        $envelope["to"] = "foo@test.com";
        $envelope["subject"] = "TEST MESSAGE";
        $envelope["from"] = 'bar@test.com';
        $envelope["cc"] = 'baz@test.com';
        $envelope["bcc"] = 'qux@test.com';

        $part["type"] = TYPETEXT;
        $part["subtype"] = "plain";
        $part["description"] = "part description";
        $part["contents.data"] = "Testing Content";

        $body[1] = $part;

        $msg = imap_mail_compose($envelope, $body);

        if (imap_append($this->mailManager->getConnection(), $this->mailManager->getMailboxName(), $msg) === false) {
            die("could not append message: " . imap_last_error());
        }
    }
}
