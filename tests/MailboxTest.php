<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 24/01/16
 * Time: 15:49
 */

namespace Humps\MailManager\Tests;


use Humps\MailManager\Components\Mailbox;

class MailboxTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Mailbox $mailbox
     */
    protected $mailbox;

    public function setUp()
    {
        $this->mailbox = new Mailbox('imap.example.com','USERNAME','PASSWORD', 993);
    }

    /**
     * @test
     */
    public function it_sets_the_server()
    {
        $this->mailbox->setServer('foo');
        $this->assertEquals('foo', $this->mailbox->getServer());
    }

    /**
     * @test
     */
    public function it_sets_the_username()
    {
        $this->mailbox->setUsername('foo');
        $this->assertEquals('foo', $this->mailbox->getUsername());
    }

    /**
     * @test
     */
    public function it_sets_the_password()
    {
        $this->mailbox->setPassword('foo');
        $this->assertEquals('foo', $this->mailbox->getPassword());
    }

    /**
     * @test
     */
    public function it_sets_the_port()
    {
        $this->mailbox->setPort(123);
        $this->assertEquals(123, $this->mailbox->getPort());
    }

    /**
     * @test
     */
    public function it_sets_the_folder()
    {
        $this->mailbox->setFolder('Trash');
        $this->assertEquals('Trash', $this->mailbox->getFolder());
    }

    /**
     * @test
     */
    public function it_sets_if_ssl()
    {
        $this->mailbox->setSsl(false);
        $this->assertFalse($this->mailbox->isSsl());
    }


    /**
     * @test
     */
    public function it_sets_if_validate_cert()
    {
        $this->mailbox->setValidateCert(false);
        $this->assertFalse($this->mailbox->isValidateCert());
    }

    /**
     * @test
     */
    public function it_gets_the_mailbox_name_with_folder()
    {
        $this->assertEquals('{imap.example.com:993/imap/ssl}INBOX', $this->mailbox->getMailboxName());
    }

    /**
     * @test
     */
    public function it_gets_the_mailbox_name_with_no_ssl()
    {
        $this->mailbox->setSsl(false);
        $this->assertEquals('{imap.example.com:993}INBOX', $this->mailbox->getMailboxName());
    }

    /**
     * @test
     */
    public function it_gets_the_mailbox_name_with_no_validate_cert()
    {
        $this->mailbox->setValidateCert(false);
        $this->assertEquals('{imap.example.com:993/imap/ssl/novalidate-cert}INBOX', $this->mailbox->getMailboxName());
    }


    /**
     * @test
     */
    public function it_gets_the_mailbox_name_with_no_folder()
    {
        $this->assertEquals('{imap.example.com:993/imap/ssl}', $this->mailbox->getMailboxName(true));
    }

    /**
     * @test
     */
    public function it_returns_the_mailbox_when_cast_to_string()
    {
        $this->assertEquals('{"server":"imap.example.com","username":"USERNAME","password":"PASSWORD","port":993,"folder":"INBOX","ssl":true,"validateCert":true}', $this->mailbox->__toString());
    }

}
