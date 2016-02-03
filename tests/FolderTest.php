<?php
namespace Humps\MailManager\Tests;

use Humps\MailManager\Components\Folder;
use PHPUnit_Framework_TestCase;

class FolderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Folder
     */
    protected $folder;

    public function setup()
    {
        $this->folder = Folder::create([
            'name'       => "{imap.example.com}INBOX.Trash",
            'attributes' => [1, 2, 3],
            'delimiter'  => "/"
        ]);
    }

    /**
     * @test
     */
    public function it_creates_a_folder_from_an_object()
    {
        $folder = Folder::create((object)[
            'name'       => "{imap.example.com}INBOX.Trash",
            'attributes' => [1, 2, 3],
            'delimiter'  => "/"
        ]);

        $this->assertEquals([
            'name'       => "{imap.example.com}INBOX.Trash",
            'attributes' => [1, 2, 3],
            'delimiter'  => "/"
        ], $folder->getFolder());
    }

    /**
     * @test
     */
    public function it_sets_the_folder_object()
    {
        $folder = [
            'name'       => "{imap.example.com}INBOX.Trash",
            'attributes' => [1, 2, 3],
            'delimiter'  => "/"
        ];

        $this->folder->setFolder($folder);

        $this->assertEquals($folder, $this->folder->getFolder());
    }

    /**
     * @test
     */
    public function it_gets_the_name_of_the_mailbox()
    {
        $this->assertEquals('{imap.example.com}INBOX.Trash', $this->folder->getMailboxName());
    }

    /**
     * @test
     */
    public function it_sets_the_name_of_the_mailbox()
    {
        $this->folder->setMailboxName('{imap.example.com}INBOX');
        $this->assertEquals('{imap.example.com}INBOX', $this->folder->getMailboxName());
    }

    /**
     * @test
     */
    public function it_sets_the_name_of_the_folder()
    {
        $this->folder->setName('SENT');
        $this->assertEquals('SENT', $this->folder->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_name_of_the_folder()
    {
        $this->assertEquals('INBOX.Trash', $this->folder->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_attributes()
    {
        $this->assertEquals([1, 2, 3], $this->folder->getAttributes());
    }

    /**
     * @test
     */
    public function it_sets_the_attributes()
    {
        $this->folder->setAttributes([4, 5, 6]);
        $this->assertEquals([4, 5, 6], $this->folder->getAttributes());
    }

    /**
     * @test
     */
    public function it_gets_the_delimiter()
    {
        $this->assertEquals('/', $this->folder->getDelimiter());
    }

    /**
     * @test
     */
    public function it_sets_the_delimiter()
    {
        $this->folder->setDelimiter('-');
        $this->assertEquals('-', $this->folder->getDelimiter());
    }

    /**
     * @test
     */
    public function it_serializes_to_json()
    {
        $json = json_encode($this->folder);
        $arr = json_decode($json, true);

        $this->assertEquals('{imap.example.com}INBOX.Trash', $arr['mailboxName']);
        $this->assertEquals('INBOX.Trash', $arr['name']);
        $this->assertEquals([1,2,3], $arr['attributes']);
        $this->assertEquals('/', $arr['delimiter']);
        $this->assertFalse(isset($arr['folder']));
    }

	/**
	 * @test
	 */
	public function it_converts_the_object_to_a_string() {
		$this->assertEquals('{"mailboxName":"{imap.example.com}INBOX.Trash","name":"INBOX.Trash","attributes":[1,2,3],"delimiter":"\/"}', $this->folder->__toString());
	}
}
