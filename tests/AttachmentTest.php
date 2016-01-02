<?php

namespace Humps\MailManager\Tests;


use Humps\MailManager\Attachment;

class AttachmentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Attachment
     */
    protected $attachment;

    public function setUp()
    {
        $this->attachment = Attachment::create(['filename' => 'foo', 'part' => '1.1.1', 'encoding' => 1]);
    }

    /**
     * @test
     */
    public function it_creates_an_attachment_from_an_object()
    {
        $attachment = Attachment::create((object)['filename' => 'foo', 'part' => '1.1.1', 'encoding' => 1]);
        $this->assertEquals(['filename' => 'foo', 'part' => '1.1.1', 'encoding' => 1], $attachment->getAttachment());
    }

    /**
     * @test
     */
    public function it_gets_the_filename_of_the_attachment()
    {
        $this->assertEquals('foo', $this->attachment->getFilename());
    }

    /**
     * @test
     */
    public function it_sets_the_filename_of_the_attachment()
    {
        $this->attachment->setFilename('bar');
        $this->assertEquals('bar', $this->attachment->getFilename());
    }

    /**
     * @test
     */
    public function it_gets_the_part_of_the_attachment()
    {
        $this->assertEquals('1.1.1', $this->attachment->getPart());
    }

    /**
     * @test
     */
    public function it_sets_the_part_of_the_attachment()
    {
        $this->attachment->setPart('2.2.2');
        $this->assertEquals('2.2.2', $this->attachment->getPart());
    }

    /**
     * @test
     */
    public function it_gets_the_encoding_of_the_attachment()
    {
        $this->assertEquals(1, $this->attachment->getEncoding());
    }

    /**
     * @test
     */
    public function it_sets_the_encoding_of_the_attachment()
    {
        $this->attachment->setEncoding(2);
        $this->assertEquals(2, $this->attachment->getEncoding());
    }

    /**
     * @test
     */
    public function it_gets_the_attachment_array()
    {
        $this->assertTrue(is_array($this->attachment->getAttachment()));
    }

    /**
     * @test
     */
    public function it_sets_the_attachment_array()
    {
        $this->attachment->setAttachment(['filename' => 'bar', 'part' => '1.1.1', 'encoding' => 1]);
        $this->assertTrue(is_array($this->attachment->getAttachment()));
        $this->assertEquals('bar', $this->attachment->getAttachment()['filename']);
    }

    /**
     * @test
     */
    public function it_serializes_to_json()
    {
        $json = json_encode($this->attachment);
        $arr = json_decode($json, true);
        $this->assertEquals('foo', $arr['filename']);
        $this->assertEquals('1.1.1', $arr['part']);
        $this->assertEquals(1, $arr['encoding']);
        $this->assertFalse(isset($arr['attachment']));
    }
}
