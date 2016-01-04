<?php
namespace Humps\MailManager\Tests;

use Humps\MailManager\ImapMailManager;
use Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper;
use Mockery as m;
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
        $mailManager = new ImapMailManagerTestHelper(true, 'INBOX', __DIR__.'/config/imap_config_no_cert.php');
        $this->assertEquals('{imap.example.com:993/imap/ssl/novalidate-cert}INBOX', $mailManager->getMailboxName());
    }

    /**
     * @test
     */
    public function it_should_return_the_mailbox_name_with_no_ssl()
    {
        $mailManager = new ImapMailManagerTestHelper(true, 'INBOX', __DIR__.'/config/imap_config_no_ssl.php');
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
        $messages = $mailManager->searchMessages(['from' => 'foo']);
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
        $this->assertEquals(2, count($messages));
    }

    /**
     * @test
     */
    public function it_gets_the_message_numbers()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $messages = $mailManager->searchMessages('FROM', 'foo');
        $messageNumbers = ImapMailManager::getMessageNumbers($messages);
        $this->assertEquals(2, count($messageNumbers));
        $this->assertEquals([2, 2], $messageNumbers);
    }

    /**
     * @test
     */
    public function it_gets_the_message_list()
    {
        $mailManager = new ImapMailManagerTestHelper();
        $messages = $mailManager->searchMessages('FROM', 'foo');
        $messageList = ImapMailManager::getMessageList($messages);
        $this->assertEquals('2,2', $messageList);
    }

    /**
     * @test
     */
    public function it_gets_all_messages()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with("ALL", SORTSUBJECT, false, FT_PEEK, true);
        $mock->getAllMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_unread_messages()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with("UNSEEN", SORTSUBJECT, false, FT_PEEK, true);
        $mock->getUnreadMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_by_sender()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with(['from' => 'foo@bar.com'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBySender('foo@bar.com', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */

    public function it_gets_messages_by_subject()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with(['subject' => 'my subject'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBySubject('my subject', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */

    public function it_gets_messages_by_cc()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with(['cc' => 'foo@bar.com'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesByCc('foo@bar.com', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_by_bcc()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with(['bcc' => 'foo@bar.com'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesByBcc('foo@bar.com', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_by_receiver()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with(['to' => 'foo@bar.com'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesByReceiver('foo@bar.com', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_on_date()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with(['on' => '03-Jan-2016'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesByDate('2016-01-03', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_before_date()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with(['before' => '03-Jan-2016'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBefore('2016-01-03', false, SORTSUBJECT, false, true);
    }


    /**
     * @test
     */
    public function it_gets_messages_after_dates()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with(['since' => '03-Jan-2016'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesAfter('2016-01-03', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_between_dates()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with([
            'since'  => '03-Jan-2016',
            'before' => '05-Jan-2016'
        ], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBetween('2016-01-03', '2016-01-04', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_to_date_is_earlier_than_from_date()
    {
        $this->setExpectedException('Exception');
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with([
            'since'  => '03-Jan-2016',
            'before' => '05-Jan-2016'
        ], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBetween('2016-01-04', '2016-01-03', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_between_dates_when_both_dates_are_the_same()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with([
            'since'  => '03-Jan-2016',
            'before' => '04-Jan-2016'
        ], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBetween('2016-01-03', '2016-01-03', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_read_messages()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with('SEEN', SORTSUBJECT, false, 0, true);
        $mock->getReadMessages(SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_important_messages()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with('FLAGGED', SORTSUBJECT, false, FT_PEEK, true);
        $mock->getImportantMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_answered_messages()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with('ANSWERED', SORTSUBJECT, false, FT_PEEK, true);
        $mock->getAnsweredMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_unanswered_messages()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[searchMessages]');
        $mock->shouldReceive('searchMessages')->with('UNANSWERED', SORTSUBJECT, false, FT_PEEK, true);
        $mock->getUnansweredMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_flags_the_message_as_read()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[setFlag]');
        $mock->shouldReceive('setFlag')->with('1,2', '\Seen');
        $mock->flagAsRead('1,2');
    }

    /**
     * @test
     */
    public function it_flags_the_message_as_important()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[setFlag]');
        $mock->shouldReceive('setFlag')->with('1,2', '\Flagged');
        $mock->flagAsImportant('1,2');
    }

    /**
     * @test
     */
    public function it_flags_the_message_as_answered()
    {
        $mock = m::mock('Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper[setFlag]');
        $mock->shouldReceive('setFlag')->with('1,2', '\Answered');
        $mock->flagAsAnswered('1,2');
    }

    /**
     * @test
     */
    public function it_gets_a_list_of_folders()
    {

    }
}
