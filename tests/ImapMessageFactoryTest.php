<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 10/01/16
 * Time: 11:17
 */


namespace Humps\MailManager\Tests;

use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\Factories\ImapMessageFactory;
use Humps\MailManager\Components\ImapMessage;
use Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper;
use Mockery as m;

class ImapMessageFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $message;

    public function setUp()
    {
        /**
         * @var Imap $imap
         */
        $imap = m::mock('Humps\MailManager\Contracts\Imap');
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());

        /**
         * @var ImapMessage $message
         */
        $this->message = ImapMessageFactory::create(1, $imap);
    }


    /**
     * @test
     */
    public function it_returns_an_ImapMessage_object()
    {

        $this->assertInstanceOf('Humps\MailManager\Components\ImapMessage', $this->message);
    }

    /**
     * @test
     */
    public function it_sets_the_message_number()
    {
        $this->assertNotNull($this->message->getMessageNum());
    }

    /**
     * @test
     */
    public function it_sets_the_uid()
    {
        $this->assertNotNull($this->message->getUid());
    }

    /**
     * @test
     */
    public function it_sets_the_subject()
    {
        $this->assertNotNull($this->message->getSubject());
    }

    /**
     * @test
     */
    public function it_sets_the_from_email()
    {
        $this->assertNotNull($this->message->getFrom());
    }

    /**
     * @test
     */
    public function it_sets_the_to_email()
    {
        $this->assertNotNull($this->message->getTo());
    }

    /**
     * @test
     */
    public function it_sets_the_cc_email()
    {
        $this->assertNotNull($this->message->getCc());
    }

    /**
     * @test
     */
    public function it_sets_the_bcc_email()
    {
        $this->assertNotNull($this->message->getBcc());
    }

    /**
     * @test
     */
    public function it_sets_the_html_body()
    {
        $this->assertNotNull($this->message->getHtmlBody());
    }


    /**
     * @test
     */
    public function it_sets_the_text_body()
    {
        $this->assertNotNull($this->message->getTextBody());
    }


    /**
     * @test
     */
    public function it_sets_the_attachments()
    {
        $this->assertNotNull($this->message->getAttachments());
    }


    /**
     * @test
     */
    public function it_sets_the_size()
    {
        $this->assertNotNull($this->message->getSize());
    }


    /**
     * @test
     */
    public function it_sets_the_date()
    {
        $this->assertNotNull($this->message->getDate());
    }

    /**
     * @test
     */
    public function it_sets_the_important_flag()
    {
        $this->assertNotNull($this->message->isImportant());
    }

    /**
     * @test
     */
    public function it_sets_the_read_flag()
    {
        $this->assertNotNull($this->message->isRead());
    }


    /**
     * @test
     */
    public function it_sets_the_answered_flag()
    {
        $this->assertNotNull($this->message->isAnswered());
    }

    /**
     * @test
     */
    public function it_sets_the_message_structure()
    {
        $this->assertNotNull($this->message->getStructure());
    }

    /**
     * @test
     */
    public function it_sets_the_message_headers()
    {
        $this->assertNotNull($this->message->getHeaders());
    }


    /**
     * @test
     */
    public function it_peeks_at_the_message()
    {
        /**
         * @var Imap $imap
         */
        $imap = m::mock('Humps\MailManager\Contracts\Imap');
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
        $imap->shouldReceive('fetchBody')->once()->with(1, "1.1", FT_PEEK);
        $imap->shouldReceive('fetchBody')->once()->with(1, "1.2", FT_PEEK);

        ImapMessageFactory::create(1, $imap, false, true);

    }

    /**
     * @test
     */
    public function it_excludes_the_body_from_the_message()
    {
        /**
         * @var Imap $imap
         */
        $imap = m::mock('Humps\MailManager\Contracts\Imap');
        $imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
        $imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());

        $message = ImapMessageFactory::create(1, $imap, true);

        $imap->shouldNotHaveReceived('fetchBody');
        $this->assertNull($message->getTextBody());
        $this->assertNull($message->getHtmlBody());
    }


}
