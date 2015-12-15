<?php
namespace Humps\MailManager\Tests;

use Humps\MailManager\Folder;
use PHPUnit_Framework_TestCase;

class FolderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Folder
     */
    protected $folder;

    public function setup()
    {
        $this->folder = new Folder((object)[
            'name' => "{imap.example.com}INBOX.Trash",
            'attributes' => 'foo',
            'delimiter' => "/"
        ]);
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
    public function it_gets_the_name_of_the_folder()
    {
        $this->assertEquals('INBOX.Trash', $this->folder->getName());
    }

    /**
     * @test
     */
    public function it_gets_the_attributes()
    {
        $this->assertEquals('foo', $this->folder->getAttributes());
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
    public function it_returns_an_array_of_all_folder_details()
    {
        $details = $this->folder->getDetails();
        $this->assertInternalType('array', $details);
        $this->assertTrue(count($details) === 3);
    }
}
