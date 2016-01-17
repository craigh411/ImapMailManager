<?php
namespace Humps\MailManager\Tests;

use Humps\MailManager\Config;
use Humps\MailManager\ImapHandler;
use Humps\MailManager\ImapMailManager;
use Humps\MailManager\Tests\Helpers\ImapMailManagerEmbeddedImageHelper;
use Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper;
use Mockery as m;
use PHPUnit_Framework_TestCase;


class ImapMailManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_loads_the_config_file()
    {
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);
        $this->assertInstanceOf('Humps\MailManager\Config', $mailManager->getConfig());
    }

    /**
     * @test
     */
    public function it_returns_a_message_objects_headers_only()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());;
        $message = $mailManager->getMessage(1, 0, true);
        $this->assertInstanceOf('Humps\MailManager\Components\ImapMessage', $message);
        $this->assertNull($message->getHtmlBody());
        $this->assertNull($message->getTextBody());
    }

    /**
     * @test
     */
    public function it_returns_a_message_object()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());

        $message = $mailManager->getMessage(1);

        $this->assertInstanceOf('Humps\MailManager\Components\ImapMessage', $message);
        $this->assertNotNull($message->getHtmlBody());
        $this->assertNotNull($message->getTextBody());
    }

    /**
     * @test
     */
    public function it_returns_an_attachment()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());

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
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $config->attachment_download_path = 'attachments';

        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody(2));
        $imap->shouldReceive('getFolderName')->andReturn('inbox');

        $mailManager->downloadAttachments(1);
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
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $config->attachment_download_path = 'attachments';

        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());
        $imap->shouldReceive('getFolderName')->andReturn('INBOX');

        $mailManager->downloadAttachments(1, 'apple.png');
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
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $config->attachment_download_path = 'attachments';

        $mailManager = new ImapMailManager($imap, $config);
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());
        $imap->shouldReceive('getFolderName')->andReturn('INBOX');

        $mailManager->downloadAttachments(1, ['apple.png']);
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
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $config->attachment_download_path = 'attachments';

        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());
        $imap->shouldReceive('getFolderName')->andReturn('INBOX');

        $mailManager->downloadAttachments(1, ['foo.png']);
        $this->assertFileNotExists('attachments/inbox/1/apple.png');
    }

    /**
     * @test
     */
    public function it_returns_an_array_of_body_parts()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $bodyParts = $mailManager->fetchBodyParts(ImapMailManagerTestHelper::fetchStructure());
        $this->assertEquals(2, count($bodyParts));
        $this->assertInstanceOf('Humps\MailManager\Components\ImapBodyPart', $bodyParts[0][0]);
    }

    /**
     * @test
     */
    public function it_returns_a_collection_of_messages_from_string_search()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('sort')->with('ALL',SORTDATE,true)->andReturn([1, 2]);
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());


        $messages = $mailManager->searchMessages('ALL');
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
        $this->assertEquals(2, count($messages));
    }

    /**
     * @test
     */
    public function it_returns_a_collection_of_messages_from_array_search()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('sort')->with('TO "foo@bar.com" TO "bar@baz.com" ', SORTDATE, true)->andReturn([1, 2]);
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());


        $messages = $mailManager->searchMessages(['to' => ['foo@bar.com','bar@baz.com']]);
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
        $this->assertEquals(2, count($messages));
    }

    /**
     * @test
     */
    public function it_returns_a_collection_of_messages_when_search_criteria_is_passed_as_string()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('sort')->andReturn([1, 2]);
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());

        $messages = $mailManager->searchMessages('FROM', 'foo');
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
        $this->assertEquals(2, count($messages));
    }

    /**
     * @test
     */
    public function it_returns_a_collection_of_messages_when_search_criteria_is_passed_as_array()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('sort')->andReturn([1, 2]);
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());

        $messages = $mailManager->searchMessages(['from' => 'foo']);
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
        $this->assertEquals(2, count($messages));
    }

    /**
     * @test
     */
    public function it_gets_the_message_numbers()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('sort')->andReturn([1, 2]);
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());

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
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('sort')->andReturn([1, 2]);
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());

        $messages = $mailManager->searchMessages('FROM', 'foo');
        $messageList = ImapMailManager::getMessageList($messages);
        $this->assertEquals('2,2', $messageList);
    }

    /**
     * This test just makes sure the correct params are being passed to searchMessages.
     * @test
     */
    public function it_gets_all_messages()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with("ALL", SORTSUBJECT, false, FT_PEEK, true);
        $mock->getAllMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_unread_messages()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with("UNSEEN", SORTSUBJECT, false, FT_PEEK, true);
        $mock->getUnreadMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_by_sender()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with(['from' => 'foo@bar.com'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBySender('foo@bar.com', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */

    public function it_gets_messages_by_subject()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with(['subject' => 'my subject'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBySubject('my subject', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */

    public function it_gets_messages_by_cc()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with(['cc' => 'foo@bar.com'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesByCc('foo@bar.com', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_by_bcc()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with(['bcc' => 'foo@bar.com'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesByBcc('foo@bar.com', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_by_receiver()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with(['to' => 'foo@bar.com'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesByReceiver('foo@bar.com', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_on_date()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with(['on' => '03-Jan-2016'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesByDate('2016-01-03', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_before_date()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with(['before' => '03-Jan-2016'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesBefore('2016-01-03', false, SORTSUBJECT, false, true);
    }


    /**
     * @test
     */
    public function it_gets_messages_after_dates()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with(['since' => '03-Jan-2016'], SORTSUBJECT, false, FT_PEEK, true);
        $mock->getMessagesAfter('2016-01-03', false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_messages_between_dates()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);

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
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $mailManager->getMessagesBetween('2016-01-04', '2016-01-03');
    }

    /**
     * @test
     */
    public function it_gets_messages_between_dates_when_both_dates_are_the_same()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);

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
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with('SEEN', SORTSUBJECT, false, 0, true);
        $mock->getReadMessages(SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_important_messages()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with('FLAGGED', SORTSUBJECT, false, FT_PEEK, true);
        $mock->getImportantMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_answered_messages()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with('ANSWERED', SORTSUBJECT, false, FT_PEEK, true);
        $mock->getAnsweredMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_gets_unanswered_messages()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mock = m::mock('Humps\MailManager\ImapMailManager[searchMessages]', [$imap, $config]);
        $mock->shouldReceive('searchMessages')->with('UNANSWERED', SORTSUBJECT, false, FT_PEEK, true);
        $mock->getUnansweredMessages(false, SORTSUBJECT, false, true);
    }

    /**
     * @test
     */
    public function it_flags_the_message_as_read()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('setFlag')->with('1,2', '\Seen');
        $mailManager->flagAsRead('1,2');
    }

    /**
     * @test
     */
    public function it_flags_the_message_as_important()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('setFlag')->with('1,2', '\Flagged');
        $mailManager->flagAsImportant('1,2');
    }

    /**
     * @test
     */
    public function it_flags_the_message_as_answered()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('setFlag')->with('1,2', '\Answered');
        $mailManager->flagAsAnswered('1,2');
    }

    /**
     * @test
     */
    public function it_gets_a_list_of_all_folders()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getFolders')->andReturn(ImapMailManagerTestHelper::getMailboxFolders());
        $imap->shouldReceive('getMailboxName')->andReturn('{imap.example.com:993/imap/ssl}INBOX');

        $folders = $mailManager->getAllFolders();

        $this->assertInstanceOf('Humps\MailManager\Collections\FolderCollection', $folders);
        $this->assertEquals(10, count($folders));
        $this->assertEquals('INBOX', $folders->get(0)->getName());
        $this->assertEquals('/', $folders->get(0)->getDelimiter());
        $this->assertEquals(64, $folders->get(0)->getAttributes());
    }

    /**
     * @test
     */
    public function it_downloads_the_embedded_images()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $config->download_embedded_images = true;
        $config->embedded_image_path = 'images';
        $mailManager = new ImapMailManager($imap, $config);


        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerEmbeddedImageHelper::fetchBody());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerEmbeddedImageHelper::fetchStructure());
        $imap->shouldReceive('getFolderName')->andReturn('INBOX');

        $message = $mailManager->getMessage(1);

        // It's saved the images to the correct location
        $this->assertFileExists('images/INBOX/2/google_logo.png');
        $this->assertFileExists('images/INBOX/2/wrench.png');
        // It has replaced the img src with the saved image path.
        $this->assertContains('images/INBOX/2/wrench.png"', $message->getHtmlBody());
        $this->assertContains('images/INBOX/2/google_logo.png"', $message->getHtmlBody());
        $this->assertNotContains('cid:', $message->getHtmlBody());

        //Cleanup
        unlink('images/INBOX/2/wrench.png');
        unlink('images/INBOX/2/google_logo.png');
        rmdir('images/INBOX/2');
        rmdir('images/INBOX');
        rmdir('images');
    }

    /**
     * @test
     */
    public function it_gets_the_unread_message_count()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getStatus')->andReturn((object)['unseen' => 2]);
        $this->assertEquals(2, $mailManager->getUnreadMessageCount());
    }

    /**
     * @test
     */
    public function it_empties_the_trash()
    {
        /**
         * @var ImapHandler $imap
         */
        $imap = m::mock('Humps\MailManager\Imap');
        $mailbox = m::mock('Humps\MailManager\Mailbox');
        // It should have retrieved the alias from the config file which is INBOX.Trash
        $mailbox->shouldReceive('setFolder')->with('INBOX.Trash');

        /**
         * @var Config $config
         */
        $config = m::mock('Humps\MailManager\Config');
        $config->shouldReceive('getFolderNameByAlias')->andReturn('INBOX.Trash');

        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMailbox')->andReturn($mailbox);
        $imap->shouldReceive('setMessagesForDeletion')->andReturn(true);
        $imap->shouldReceive('deleteMessages')->andReturn(true);
        $imap->shouldReceive('refresh')->andReturn(true);

        $this->assertTrue($mailManager->emptyTrash());
    }

    /**
     * @test
     */
    public function it_moves_messages_to_the_trash()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $config->shouldReceive('getFolderNameByAlias')->andReturn('INBOX.Trash');

        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('moveMessages')->with('1,2','INBOX.Trash')->andReturn(true);

        $this->assertTrue($mailManager->moveToTrash('1,2', 'trash'));
    }

    /**
     * @test
     */
    public function it_creates_a_top_level_folder()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();

        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMailboxName')->andReturn('{imap.example.com:993/imap/ssl}');
        $imap->shouldReceive('createMailbox')->with('{imap.example.com:993/imap/ssl}FOO')->andReturn(true);
        $mailManager->createFolder('FOO');
    }

    /**
     * @test
     */
    public function it_creates_a_child_level_folder()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMailboxName')->andReturn('{imap.example.com:993/imap/ssl}INBOX');
        $imap->shouldReceive('createMailbox')->with('{imap.example.com:993/imap/ssl}INBOX.FOO')->andReturn(true);

        $mailManager->createFolder('FOO', false);
    }

    /**
     * @test
     */
    public function it_creates_a_child_level_folder_with_a_passed_delimiter()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMailboxName')->andReturn('{imap.example.com:993/imap/ssl}INBOX');
        $imap->shouldReceive('createMailbox')->with('{imap.example.com:993/imap/ssl}INBOX/FOO')->andReturn(true);

        $mailManager->createFolder('FOO', false, '/');
    }

    /**
     * @test
     */
    public function it_deletes_a_folder()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMailboxName')->andReturn('{imap.example.com:993/imap/ssl}');
        $imap->shouldReceive('deleteMailbox')->with('{imap.example.com:993/imap/ssl}FOO')->andReturn(true);
        $mailManager->deleteFolder('FOO');
    }

    /**
     * @test
     */
    public function it_returns_an_imap_object()
    {
        /**
         * @var ImapHandler $imap
         */
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $this->assertInstanceOf('Humps\MailManager\Imap',$mailManager->getImap());
    }

    /**
     * @test
     */
    public function it_returns_the_imap_connection()
    {
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getConnection')->andReturn(true);
        $this->assertEquals(true, $mailManager->getImapConnection());
    }

    /**
     * @test
     */
    public function it_gets_the_message_by_uid()
    {
        list($imap, $config) = $this->getMocks();
        $mailManager = new ImapMailManager($imap, $config);

        $imap->shouldReceive('getMessageNumber')->andReturn(1);
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());

        $message = $mailManager->getMessageByUid('foo');
        $this->assertInstanceOf('Humps\MailManager\Components\ImapMessage',$message);
    }

    /**
     * @return array
     */
    private function getMocks()
    {
        /**
         * @var ImapHandler $imap
         */
        $imap = m::mock('Humps\MailManager\Imap');

        /**
         * @var Config $config
         */
        $config = m::mock('Humps\MailManager\Config');
        return [$imap, $config];
    }
}
