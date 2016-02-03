<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 24/01/16
 * Time: 18:04
 */

namespace Humps\MailManager\Tests;

use Humps\MailManager\Collections\ImapAttachmentCollection;
use Mockery as m;

class ImapAttachmentCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_throws_an_error_when_a_non_Attachment_Collectable_is_added_by_index()
    {
        $this->setExpectedException('InvalidArgumentException');
        $collectable = m::mock('Humps\MailManager\Collections\Contracts\Collectable');
        $imapAttachmentCollection = new ImapAttachmentCollection();
        $imapAttachmentCollection[] = $collectable;
    }

    /**
     * @test
     */
    public function it_adds_an_attachment_to_the_collection()
    {
        $attachment = m::mock('Humps\MailManager\Components\ImapAttachment');
        $imapAttachmentCollection = new ImapAttachmentCollection();
        $imapAttachmentCollection[] = $attachment;
        $this->assertEquals(1, count($imapAttachmentCollection));
    }
}
