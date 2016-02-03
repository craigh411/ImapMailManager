<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 25/01/16
 * Time: 11:50
 */
namespace Humps\MailManager\Tests;

use Humps\MailManager\Factories\ImapFolderCollectionFactory;
use Mockery as m;

class ImapFolderCollectionFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function it_creates_a_FolderCollection_object()
	{
		$folders = $this->getFolders();
		$folderCollection = ImapFolderCollectionFactory::create($folders);
		$this->assertInstanceOf('Humps\MailManager\Collections\FolderCollection', $folderCollection);
		$this->assertEquals(2,count($folderCollection));
	}


	public function getFolders()
	{
		return [
			['name' => '{imap.example.com:993/imap/ssl}Inbox', 'attributes' => 64, 'delimiter' => '/',],
			['name' => '{imap.example.com:993/imap/ssl}Trash', 'attributes' => 64, 'delimiter' => '/',]
		];
	}
}