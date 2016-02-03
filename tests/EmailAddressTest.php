<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 29/12/15
 * Time: 12:32
 */

namespace Humps\MailManager\Tests;


use Humps\MailManager\Components\EmailAddress;

class EmailAddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Humps\MailManager\Components\EmailAddress
     */
    protected $email;

    public function setUp()
    {
        $this->email = EmailAddress::create(['mailbox' => 'foo', 'host' => 'bar.com', 'personal' => 'Tom Jones']);
    }

    /**
     * @test
     */
    public function it_creates_an_email_address_from_an_object()
    {
        $this->email = EmailAddress::create((object)['mailbox' => 'foo', 'host' => 'bar.com', 'personal' => 'Tom Jones']);
    }
    /**
     * @test
     */
    public function it_gets_the_mailbox()
    {
        $this->assertEquals('foo', $this->email->getMailbox());
    }

    /**
     * @test
     */
    public function it_sets_the_mailbox()
    {
        $this->email->setMailbox('bar');
        $this->assertEquals('bar', $this->email->getMailbox());
    }

    /**
     * @test
     */
    public function it_gets_the_host()
    {
        $this->assertEquals('bar.com', $this->email->getHost());
    }

    /**
     * @test
     */
    public function it_sets_the_host()
    {
        $this->email->setHost('foo.com');
        $this->assertEquals('foo.com', $this->email->getHost());
    }

    /**
     * @test
     */
    public function it_gets_the_email_address()
    {
        $this->assertEquals('foo@bar.com', $this->email->getEmailAddress());
    }

    /**
     * @test
     */
    public function it_gets_the_personal_field()
    {
        $this->assertEquals('Tom Jones', $this->email->getPersonal());
    }

    /**
     * @test
     */
    public function it_sets_the_personal_field()
    {
        $this->email->setPersonal('Dave Brown');
        $this->assertEquals('Dave Brown', $this->email->getPersonal());
    }

    /**
     * @test
     */
    public function it_get_the_email_array()
    {
        $this->assertEquals(['mailbox' => 'foo', 'host' => 'bar.com', 'personal' => 'Tom Jones'], $this->email->getEmail());
    }

    /**
     * @test
     */
    public function it_sets_the_email_array()
    {
        $this->email->setEmail(['mailbox' => 'bar', 'host' => 'foo.com', 'personal' => 'Dave Brown']);
        $this->assertEquals(['mailbox' => 'bar', 'host' => 'foo.com', 'personal' => 'Dave Brown'], $this->email->getEmail());
    }

    /**
     * @test
     */
    public function it_serializes_to_json()
    {
        $json = json_encode($this->email);
        $arr = json_decode($json, true);
        $this->assertEquals('foo', $arr['mailbox']);
        $this->assertEquals('bar.com', $arr['host']);
        $this->assertEquals('foo@bar.com', $arr['emailAddress']);
        $this->assertEquals('Tom Jones', $arr['personal']);
        $this->assertFalse(isset($arr['email']));
    }

    /**
     * @test
     */
    public function it_outputs_the_email_address_with_toString()
    {
        $this->assertEquals('{"personal":"Tom Jones","emailAddress":"foo@bar.com","mailbox":"foo","host":"bar.com"}',$this->email->__toString());
    }
}
