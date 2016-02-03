<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 24/01/16
 * Time: 16:13
 */

namespace Humps\MailManager\Tests;


use Humps\MailManager\Components\ImapBodyPart;

class ImapBodyPartTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ImapBodyPart $bodyPart
     */
    protected $bodyPart;

    public function setUp()
    {
        $this->bodyPart = new ImapBodyPart('foo','foo','foo','foo');
    }

    /**
     * @test
     */
    public function it_constructs_the_object()
    {
        $bodyPart = new ImapBodyPart('foo','bar','baz','qux', 'quux', 'corge', 'grault');
        $this->assertEquals('foo', $bodyPart->getBodyType());
        $this->assertEquals('bar', $bodyPart->getEncoding());
        $this->assertEquals('baz', $bodyPart->getSubtype());
        $this->assertEquals('qux', $bodyPart->getSection());
        $this->assertEquals('quux', $bodyPart->getCharset());
        $this->assertEquals('corge', $bodyPart->getName());
        $this->assertEquals('grault', $bodyPart->getId());

    }

    /**
     * @test
     */
    public function it_sets_the_body_type()
    {
        $this->bodyPart->setBodyType('bar');
        $this->assertEquals('bar', $this->bodyPart->getBodyType());
    }

    /**
     * @test
     */
    public function it_sets_the_encoding_type()
    {
        $this->bodyPart->setEncoding('bar');
        $this->assertEquals('bar', $this->bodyPart->getEncoding());
    }

    /**
     * @test
     */
    public function it_sets_the_subtype()
    {
        $this->bodyPart->setSubType('bar');
        $this->assertEquals('bar', $this->bodyPart->getSubType());
    }

    /**
     * @test
     */
    public function it_sets_the_section()
    {
        $this->bodyPart->setSection('bar');
        $this->assertEquals('bar', $this->bodyPart->getSection());
    }

    /**
     * @test
     */
    public function it_sets_the_charset()
    {
        $this->bodyPart->setCharset('bar');
        $this->assertEquals('bar', $this->bodyPart->getCharset());
    }

    /**
     * @test
     */
    public function it_sets_the_id()
    {
        $this->bodyPart->setId('bar');
        $this->assertEquals('bar', $this->bodyPart->getId());
    }

    /**
     * @test
     */
    public function it_sets_the_name()
    {
        $this->bodyPart->setName('bar');
        $this->assertEquals('bar', $this->bodyPart->getName());
    }

	/**
	 * @test
	 */
	public function it_serializes_to_json()
	{
		$bodyPart = new ImapBodyPart('foo','bar','baz','qux', 'quux', 'corge', 'grault');
		$json = json_encode($bodyPart);
		$arr = json_decode($json, true);
		$this->assertEquals('foo', $arr['bodyType']);
		$this->assertEquals('bar', $arr['encoding']);
		$this->assertEquals('baz', $arr['subType']);
		$this->assertEquals('qux', $arr['section']);
		$this->assertEquals('quux', $arr['charset']);
		$this->assertEquals('corge', $arr['name']);
		$this->assertEquals('grault', $arr['id']);
	}

	/**
	 * @test
	 */
	public function it_converts_the_object_to_a_string() {
		$bodyPart = new ImapBodyPart('foo','bar','baz','qux', 'quux', 'corge', 'grault');
		$this->assertEquals('{"bodyType":"foo","encoding":"bar","subType":"baz","section":"qux","charset":"quux","name":"corge","id":"grault"}', $bodyPart->__toString());
	}
}
