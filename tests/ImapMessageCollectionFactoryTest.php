<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 25/01/16
 * Time: 12:13
 */
namespace Humps\MailManager\Tests;

use Humps\MailManager\Factories\ImapMessageCollectionFactory;
use Humps\MailManager\Tests\Helpers\ImapMailManagerTestHelper;
use Mockery as m;

class ImapMessageCollectionFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function it_creates_an_ImapMessageCollection_object()
	{
		$imap = m::mock('Humps\MailManager\Contracts\Imap');
		$imap->shouldReceive('getMessageHeaders')->andReturn(ImapMailManagerTestHelper::getMessageHeaders());
		$imap->shouldReceive('fetchStructure')->andReturn(ImapMailManagerTestHelper::fetchStructure());
		$imap->shouldReceive('fetchBody')->andReturn(ImapMailManagerTestHelper::fetchBody());
		$messages = ImapMessageCollectionFactory::create([1, 2], $imap);
		$this->assertInstanceOf('Humps\MailManager\Collections\ImapMessageCollection', $messages);
		$this->assertEquals(2, count($messages));
	}
}
