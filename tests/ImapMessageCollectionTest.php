<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 24/01/16
 * Time: 18:12
 */

namespace Humps\MailManager\Tests;

use Humps\MailManager\Collections\ImapMessageCollection;
use Mockery as m;

class ImapMessageCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_throws_an_error_when_a_non_ImapMessage_Collectable_is_added_by_index()
    {
        $this->setExpectedException('InvalidArgumentException');
        $collectable = m::mock('Humps\MailManager\Collections\Contracts\Collectable');
        $imapMessageCollection = new ImapMessageCollection();
        $imapMessageCollection[] = $collectable;
    }

    /**
     * @test
     */
    public function it_adds_an_attachment_to_the_collection()
    {
        $message = m::mock('Humps\MailManager\Components\ImapMessage');
        $imapMessageCollection = new ImapMessageCollection();
        $imapMessageCollection[] = $message;
        $this->assertEquals(1, count($imapMessageCollection));
    }
}
