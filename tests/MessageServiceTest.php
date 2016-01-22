<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 10/01/16
 * Time: 13:49
 */

namespace Humps\MailManager\Tests;

use ArrayIterator;
use Humps\MailManager\Components\Contracts\Attachment;
use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\Components\Contracts\Message;

use Humps\MailManager\ImapConnection;
use Humps\MailManager\ImapHandler;
use Humps\MailManager\ImapMessageService;
use Humps\MailManager\Components\Mailbox;
use Mockery as m;

use Humps\MailManager\Tests\Helpers\ImapMailManagerEmbeddedImageHelper;
use Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper;

class MessageServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_downloads_the_embedded_images()
    {
        $connection = $this->getImapConnectionMock();

        /**
         * @var Imap $imap
         */
        $imap = m::mock('Humps\MailManager\Contracts\Imap');
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerEmbeddedImageHelper::fetchBody());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerEmbeddedImageHelper::fetchStructure());
        $imap->shouldReceive('getFolderName')->andReturn('INBOX');
        $imap->shouldReceive('getConnection')->andReturn($connection);
        /**
         * @var Message $message
         */
        $message = m::mock('Humps\MailManager\Components\Contracts\Message');
        $message->shouldReceive('getBodyParts')->andReturn(ImapMailManagerEmbeddedImageHelper::getBody());
        $message->shouldReceive('getHtmlBody')->andReturn(ImapMailManagerEmbeddedImageHelper::fetchBody());
        $message->shouldReceive('getMessageNum')->andReturn(2);
        $message->shouldReceive('setHtmlBody')->with(m::on(function ($result) {
            // It has replaced the img src with the saved image path.
            if (preg_match("/images\/INBOX\/2\/wrench\.png/", $result) && preg_match("/images\/INBOX\/2\/google_logo\.png/", $result) && !preg_match("/cid/", $result)) {
                return true;
            }
            return false;
        }));

        $messageService = new ImapMessageService($message, $imap);
        $messageService->downloadEmbeddedImages('images');

        // It's saved the images to the correct location
        $this->assertFileExists('images/INBOX/2/google_logo.png');
        $this->assertFileExists('images/INBOX/2/wrench.png');

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
    public function it_saves_the_message_attachment()
    {
        $connection = $this->getImapConnectionMock();

        /**
         * @var ImapHandler $imap
         */
        $imap = $this->getImapMock($connection);

        /**
         * @var Message $message
         */
        $message = $this->getMessageMock();

        $messageService = new ImapMessageService($message, $imap);

        /**
         * @var Attachment $attachment
         */
        /**
         * @var Attachment $attachment
         */
        $attachment = $this->getAttachment('apple.png');

        $messageService->downloadAttachment($attachment, 'attachments');

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
    public function it_saves_multiple_message_attachments()
    {
        $connection = $this->getImapConnectionMock();
        $imap = $this->getImapMock($connection);
        $message = $this->getMessageMock();


        $messageService = new ImapMessageService($message, $imap);

        /**
         * @var Attachment $attachment
         */
        $attachment1 = $this->getAttachment('apple.png');
        $attachment2 = $this->getAttachment('orange.png');

        $attachments = $attachment = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $attachments->shouldReceive('count')->andReturn(2);

        $message->shouldReceive('getAttachments')->andReturn($attachments);

        $iterator = new ArrayIterator([$attachment1, $attachment2]);
        $attachments->shouldReceive('getIterator')->andReturn($iterator);

        $messageService->downloadAttachments('attachments');

        $this->assertFileExists('attachments/inbox/1/orange.png');

        // Clean up
        unlink('attachments/inbox/1/apple.png');
        unlink('attachments/inbox/1/orange.png');
        rmdir('attachments/inbox/1');
        rmdir('attachments/inbox');
        rmdir('attachments');
    }


    /**
     * @test
     */
    public function it_saves_the_attachment_with_the_given_filename()
    {
        $connection = $this->getImapConnectionMock();
        $imap = $this->getImapMock($connection);
        $message = $this->getMessageMock();


        $messageService = new ImapMessageService($message, $imap);

        /**
         * @var Attachment $attachment
         */
        $attachment1 = $this->getAttachment('apple.png');
        $attachment2 = $this->getAttachment('orange.png');

        $attachments = $attachment = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $attachments->shouldReceive('count')->andReturn(2);

        $message->shouldReceive('getAttachments')->andReturn($attachments);

        $iterator = new ArrayIterator([$attachment1, $attachment2]);
        $attachments->shouldReceive('getIterator')->andReturn($iterator);

        $path = $messageService->downloadAttachmentByFilename('orange.png', 'attachments');
        $this->assertFileExists('attachments/inbox/1/orange.png');
        $this->assertFileNotExists('attachments/inbox/1/apple.png');
        $this->assertEquals('attachments/inbox/1/orange.png', $path);

        // Clean up
        unlink('attachments/inbox/1/orange.png');
        rmdir('attachments/inbox/1');
        rmdir('attachments/inbox');
        rmdir('attachments');
    }

    /**
     * @test
     */
    public function it_returns_false_when_the_given_filename_does_not_exist()
    {
        $connection = $this->getImapConnectionMock();
        $imap = $this->getImapMock($connection);
        $message = $this->getMessageMock();


        $messageService = new ImapMessageService($message, $imap);

        /**
         * @var Attachment $attachment
         */
        $attachment1 = $this->getAttachment('apple.png');
        $attachment2 = $this->getAttachment('orange.png');

        $attachments = $attachment = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $attachments->shouldReceive('count')->andReturn(2);

        $message->shouldReceive('getAttachments')->andReturn($attachments);

        $iterator = new ArrayIterator([$attachment1, $attachment2]);
        $attachments->shouldReceive('getIterator')->andReturn($iterator);

        $path = $messageService->downloadAttachmentByFilename('pear.png', 'attachments');
        $this->assertFalse($path);
    }

    /**
     * @test
     */
    public function it_saves_the_attachment_with_the_given_part()
    {
        $connection = $this->getImapConnectionMock();
        $imap = $this->getImapMock($connection);
        $message = $this->getMessageMock();

        $messageService = new ImapMessageService($message, $imap);

        /**
         * @var Attachment $attachment
         */
        $attachment1 = $this->getAttachment('apple.png', '2');
        $attachment2 = $this->getAttachment('orange.png', '2.1');

        $attachments = $attachment = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $attachments->shouldReceive('count')->andReturn(2);

        $message->shouldReceive('getAttachments')->andReturn($attachments);

        $iterator = new ArrayIterator([$attachment1, $attachment2]);
        $attachments->shouldReceive('getIterator')->andReturn($iterator);

        $path = $messageService->downloadAttachmentByPart('2.1', 'attachments');
        $this->assertFileExists('attachments/inbox/1/orange.png');
        $this->assertFileNotExists('attachments/inbox/1/apple.png');
        $this->assertEquals('attachments/inbox/1/orange.png', $path);

        // Clean up
        unlink('attachments/inbox/1/orange.png');
        rmdir('attachments/inbox/1');
        rmdir('attachments/inbox');
        rmdir('attachments');
    }

    /**
     * @test
     */
    public function it_returns_false_when_the_part_does_not_exist()
    {
        $connection = $this->getImapConnectionMock();
        $imap = $this->getImapMock($connection);
        $message = $this->getMessageMock();

        $messageService = new ImapMessageService($message, $imap);

        /**
         * @var Attachment $attachment
         */
        $attachment1 = $this->getAttachment('apple.png', '2');
        $attachment2 = $this->getAttachment('orange.png', '2.1');

        $attachments = $attachment = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $attachments->shouldReceive('count')->andReturn(2);

        $message->shouldReceive('getAttachments')->andReturn($attachments);

        $iterator = new ArrayIterator([$attachment1, $attachment2]);
        $attachments->shouldReceive('getIterator')->andReturn($iterator);

        $path = $messageService->downloadAttachmentByPart('3', 'attachments');
        $this->assertFalse($path);

    }

    public function getAttachment($filename, $part = "2")
    {
        $attachment = m::mock('Humps\MailManager\Components\Contracts\Attachment');

        $attachment->shouldReceive('getPart')->andReturn($part);
        $attachment->shouldReceive('getEncoding')->andReturn(ENCBASE64);
        $attachment->shouldReceive('getFilename')->andReturn($filename);

        return $attachment;
    }

    /**
     * @return m\MockInterface
     */
    protected function getImapConnectionMock()
    {
        $mailbox = m::mock(Mailbox::class);
        $mailbox->shouldReceive('getFolder')->andReturn('INBOX');
        $connection = m::mock(ImapConnection::class);
        $connection->shouldReceive('getMailbox')->andReturn($mailbox);
        return $connection;
    }

    /**
     * @param $connection
     * @return ImapHandler
     */
    protected function getImapMock($connection)
    {
        /**
         * @var ImapHandler $imap
         */
        $imap = m::mock('Humps\MailManager\Contracts\Imap');
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody(2));
        $imap->shouldReceive('getFolderName')->andReturn('inbox');
        $imap->shouldReceive('getConnection')->andReturn($connection);
        return $imap;
    }

    /**
     * @return Message
     */
    protected function getMessageMock()
    {
        /**
         * @var Message $message
         */
        $message = m::mock('Humps\MailManager\Components\Contracts\Message');
        $message->shouldReceive('getMessageNum')->andReturn(1);
        return $message;
    }
}
