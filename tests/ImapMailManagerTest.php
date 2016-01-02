<?php
namespace Humps\MailManager\Tests;

use Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper;
use Mockery as m;
use Faker;
use PHPUnit_Framework_TestCase;


class ImapMailManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_loads_the_config_file_into_the_config_array()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $this->assertTrue(is_array($mailManager->getConfig()));
        $this->assertTrue(count($mailManager->getConfig()) !== false);
    }

    /**
     * @test
     */
    public function it_throws_an_error_on_failed_connection()
    {
        $this->setExpectedException('Exception', 'Unable to connect to to mailbox: {imap.example.com:993/imap/ssl}INBOX');
        new ImapMailManagerTestHelper(false);
    }

    /**
     * @test
     */
    public function it_should_return_a_mailbox_object()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $this->assertInstanceOf('Humps\MailManager\Mailbox', $mailManager->getMailbox());
    }

    /**
     * @test
     */
    public function it_should_return_the_mailbox_name()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $this->assertEquals('{imap.example.com:993/imap/ssl}INBOX', $mailManager->getMailboxName());
    }

    /**
     * @test
     */
    public function it_should_return_the_mailbox_name_with_no_cert()
    {
        $mailManager = new ImapMailManagerTestHelper(true, 'INBOX', 'config/imap_config_no_cert.php');
        $this->assertEquals('{imap.example.com:993/imap/ssl/novalidate-cert}INBOX', $mailManager->getMailboxName());
    }

    /**
     * @test
     */
    public function it_should_return_the_mailbox_name_with_no_ssl()
    {
        $mailManager = new ImapMailManagerTestHelper(true, 'INBOX', 'config/imap_config_no_ssl.php');
        $this->assertEquals('{imap.example.com:143}INBOX', $mailManager->getMailboxName());
    }

    /**
     * @test
     */
    public function it_returns_a_message_objects_headers_only()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $message = $mailManager->getMessage(1, 0, true);
        $this->assertInstanceOf('Humps\MailManager\ImapMessage', $message);
        $this->assertNull($message->getHtmlBody());
        $this->assertNull($message->getTextBody());
    }

    /**
     * @test
     */
    public function it_returns_a_message_object()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $message = $mailManager->getMessage(1);
        $this->assertInstanceOf('Humps\MailManager\ImapMessage', $message);
        $this->assertNotNull($message->getHtmlBody());
        $this->assertNotNull($message->getTextBody());
    }

    /**
     * @test
     */
    public function it_returns_an_attachment()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $attachments = $mailManager->getAttachments(1);

        $this->assertInstanceOf('Humps\MailManager\Collections\AttachmentCollection', $attachments);
        $this->assertEquals(1, count($attachments));
        $this->assertEquals('apple.png', $attachments->get(0)->getFilename());
    }

    /**
     * @test
     */
    public function it_saves_the_message_attachment()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $mailManager->downloadAttachments(1, 'attachments');
        $this->assertFileExists('attachments/inbox/1/apple.png');
        // Clean up
        unlink('attachments/inbox/1/apple.png');
        rmdir('attachments/inbox/1');
        rmdir('attachments/inbox');
        rmdir('attachments');
    }

    /**
     * @test
     */
    public function it_saves_the_message_attachment_when_the_name_is_passed_as_a_string()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $mailManager->downloadAttachments(1, 'attachments', 'apple.png');
        $this->assertFileExists('attachments/inbox/1/apple.png');
        // Clean up
        unlink('attachments/inbox/1/apple.png');
        rmdir('attachments/inbox/1');
        rmdir('attachments/inbox');
        rmdir('attachments');
    }

    /**
     * @test
     */
    public function it_saves_the_message_attachment_when_the_name_is_passed_as_an_array()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $mailManager->downloadAttachments(1, 'attachments', ['apple.png']);
        $this->assertFileExists('attachments/inbox/1/apple.png');
        // Clean up
        unlink('attachments/inbox/1/apple.png');
        rmdir('attachments/inbox/1');
        rmdir('attachments/inbox');
        rmdir('attachments');
    }

    /**
     * @test
     */
    public function it_does_not_save_the_attachment_when_a_different_filename_is_passed()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $mailManager->downloadAttachments(1, 'attachments', ['foo.png']);
        $this->assertFileNotExists('attachments/inbox/1/apple.png');
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_body_parts()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $bodyParts = $mailManager->fetchBodyParts($mailManager->fetchStructure(1));
        $this->assertEquals(2, count($bodyParts));
        $this->assertInstanceOf('Humps\MailManager\ImapBodyPart', $bodyParts[0][0]);
    }

    /**
     * @test
     */
    public function it_returns_a_collection_of_messages()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $messages = $mailManager->searchMessages('ALL');
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
        $this->assertEquals(2, count($messages));
    }

    /**
     * @test
     */
    public function it_returns_a_collection_of_messages_when_search_criteria_is_passed_as_string()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $messages = $mailManager->searchMessages('FROM', 'foo');
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
        $this->assertEquals(2, count($messages));
    }

    /**
     * @test
     */
    public function it_returns_a_collection_of_messages_when_search_criteria_is_passed_as_array()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $messages = $mailManager->searchMessages('FROM', ['foo', 'bar']);
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
        // should be 4 as it will find 2 messages for each search.
        $this->assertEquals(4, count($messages));
    }
}
