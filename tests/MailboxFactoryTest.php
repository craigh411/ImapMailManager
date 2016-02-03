<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 24/01/16
 * Time: 15:39
 */

namespace Humps\MailManager\Tests;


use Humps\MailManager\Factories\MailboxFactory;

class MailboxFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_creates_a_Mailbox_object_from_the_given_config_file()
    {
        $mailbox = MailboxFactory::create('INBOX', __DIR__.'/imap_config/imap_config.php');
        $this->assertInstanceOf('Humps\MailManager\Components\Mailbox', $mailbox);
    }

    /**
     * @test
     */
    public function it_throws_an_error_when_it_cannot_find_the_given_config_file()
    {
        $this->setExpectedException('Exception', 'Unable to find config file foo');
        MailboxFactory::create('INBOX', 'foo');
    }

    /**
     * @test
     */
    public function it_sets_the_server_from_the_config_file()
    {
        $mailbox = MailboxFactory::create('INBOX', __DIR__.'/imap_config/imap_config.php');
        $this->assertEquals('imap.example.com', $mailbox->getServer());
    }

    /**
     * @test
     */
    public function it_sets_the_username_from_the_config_file()
    {
        $mailbox = MailboxFactory::create('INBOX', __DIR__.'/imap_config/imap_config.php');
        $this->assertEquals('USERNAME', $mailbox->getUsername());
    }

    /**
     * @test
     */
    public function it_sets_the_password_from_the_config_file()
    {
        $mailbox = MailboxFactory::create('INBOX', __DIR__.'/imap_config/imap_config.php');
        $this->assertEquals('PASSWORD', $mailbox->getPassword());
    }

    /**
     * @test
     */
    public function it_sets_the_port_from_the_config_file()
    {
        $mailbox = MailboxFactory::create('INBOX', __DIR__.'/imap_config/imap_config.php');
        $this->assertEquals('993', $mailbox->getPort());
    }

    /**
     * @test
     */
    public function it_sets_the_folder()
    {
        $mailbox = MailboxFactory::create('Trash', __DIR__.'/imap_config/imap_config.php');
        $this->assertEquals('Trash', $mailbox->getFolder());
    }

    /**
     * @test
     */
    public function it_sets_ssl_from_the_config_file()
    {
        $mailbox = MailboxFactory::create('INBOX', __DIR__.'/imap_config/imap_config.php');
        $this->assertTrue($mailbox->isSsl());
    }


    /**
     * @test
     */
    public function it_sets_validate_cert_from_the_config_file()
    {
        $mailbox = MailboxFactory::create('INBOX', __DIR__.'/imap_config/imap_config.php');
        $this->assertTrue($mailbox->isValidateCert());
    }
}
