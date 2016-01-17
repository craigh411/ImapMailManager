<?php

namespace Humps\MailManager\Tests;


use Humps\MailManager\Components\ImapBodyPart;
use Humps\MailManager\ImapMailManager;
use Humps\MailManager\Components\ImapMessage;
use Mockery as m;

class ImapMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ImapMailManager
     */
    protected $mailManager;
    /**
     * @var ImapMessage
     */
    protected $message;

    public function setup()
    {
        $this->message = new ImapMessage();
    }

    
    /**
     * @test
     */
    public function it_sets_the_message_number()
    {
        $this->message->setMessageNum(123);
        $this->assertEquals($this->message->getMessageNum(), 123);
    }

    /**
     * @test
     */
    public function it_sets_the_message_uid()
    {
        $this->message->setUid('foo');
        $this->assertEquals($this->message->getUid(), 'foo');
    }

    /**
     * @test
     */
    public function it_sets_the_message_subject()
    {
        $this->message->setSubject('foo');
        $this->assertEquals($this->message->getSubject(), 'foo');
    }

    /**
     * @test
     */
    public function it_sets_the_from_email()
    {
        $emails = m::mock('Humps\MailManager\Collections\EmailCollection');
        $this->message->setFrom($emails);
        $this->assertInstanceOf('Humps\MailManager\Collections\EmailCollection', $this->message->getFrom());
    }

    /**
     * @test
     */
    public function it_sets_the_to_email()
    {
        $emails = m::mock('Humps\MailManager\Collections\EmailCollection');
        $this->message->setTo($emails);
        $this->assertInstanceOf('Humps\MailManager\Collections\EmailCollection', $this->message->getTo());
    }


    /**
     * @test
     */
    public function it_sets_the_cc_email()
    {
        $emails = m::mock('Humps\MailManager\Collections\EmailCollection');
        $this->message->setCc($emails);
        $this->assertInstanceOf('Humps\MailManager\Collections\EmailCollection', $this->message->getCc());
    }

    /**
     * @test
     */
    public function it_sets_the_bcc_email()
    {
        $emails = m::mock('Humps\MailManager\Collections\EmailCollection');
        $this->message->setBcc($emails);
        $this->assertInstanceOf('Humps\MailManager\Collections\EmailCollection', $this->message->getBcc());
    }

    /**
     * @test
     */
    public function it_sets_the_html_body()
    {
        $this->message->setHtmlBody('foo');
        $this->assertEquals($this->message->getHtmlBody(), 'foo');
    }

    /**
     * @test
     */
    public function it_sets_the_text_body()
    {
        $this->message->setTextBody('foo');
        $this->assertEquals($this->message->getTextBody(), 'foo');
    }

    /**
     * @test
     */
    public function it_sets_the_size_of_the_message()
    {
        $this->message->setSize(1000);
        $this->assertEquals($this->message->getSize(), 1000);
    }

    /**
     * @test
     */
    public function it_sets_the_date_of_the_message()
    {
        $this->message->setDate('2015-12-28');
        $this->assertEquals($this->message->getRawDate(), '2015-12-28');
        $this->assertInstanceOf('Carbon\Carbon', $this->message->getDate());
    }

    /**
     * @test
     */
    public function it_sets_the_important_flag()
    {
        $this->message->setImportant(true);
        $this->assertTrue($this->message->isImportant());
    }

    /**
     * @test
     */
    public function it_sets_the_read_flag()
    {
        $this->message->setRead(true);
        $this->assertTrue($this->message->isRead());
    }

    /**
     * @test
     */
    public function it_sets_the_answered_flag()
    {
        $this->message->setAnswered(true);
        $this->assertTrue($this->message->isAnswered());
    }

    /**
     * @test
     */
    public function it_sets_the_attachments()
    {
        $attachments = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $this->message->setAttachments($attachments);
        $this->assertInstanceOf('Humps\MailManager\Collections\ImapAttachmentCollection', $this->message->getAttachments());
    }

    /**
     * @test
     */
    public function it_returns_true_when_has_attachments()
    {
        $attachments = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $attachments->shouldReceive('count')->andReturn(1);

        $this->message->setAttachments($attachments);
        $this->assertTrue($this->message->hasAttachments());
    }

    /**
     * @test
     */
    public function it_returns_the_number_of_attachments()
    {
        $attachments = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $attachments->shouldReceive('count')->andReturn(3);

        $this->message->setAttachments($attachments);
        $this->assertEquals(3, $this->message->attachmentCount());
    }

    /**
     * @test
     */
    public function it_returns_false_when_it_doesnt_have_attachments()
    {
        $this->assertFalse($this->message->hasAttachments());
    }

    /**
     * @test
     */
    public function it_sets_the_body_parts()
    {
        $bodyPart = m::mock('Humps\MailManager\Collections\ImapBodyParts');
        $this->message->setBodyParts([$bodyPart]);
        $this->assertEquals([$bodyPart], $this->message->getBodyParts());
    }

    /**
     * @test
     */
    public function it_converts_the_message_to_json_when_converting_to_string()
    {
        $this->assertJSon($this->message->__toString());
    }

    /**
     * @test
     */
    public function it_serializes_to_json()
    {
        $json = json_encode($this->message);
        $this->assertJson($json);
    }

    /**
     * @test
     */
    public function it_returns_a_json_representation_of_itself()
    {
        $json = $this->message->toJson();
        $this->assertJson($json);
    }

    /**
     * @test
     */
    public function it_does_not_return_the_message_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertFalse(isset($arr['message']));
    }


    /**
     * @test
     */
    public function it_gets_the_message_subject_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertEquals('foo', $arr['subject']);
    }

    /**
     * @test
     */
    public function it_gets_the_message_Read_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertEquals(true, $arr['read']);
    }


    /**
     * @test
     */
    public function it_gets_the_message_is_answered_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertEquals(true, $arr['answered']);
    }

    /**
     * @test
     */
    public function it_gets_the_messages_cc_email_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['cc']));
        $this->assertEquals('foo', $arr['cc'][0]);
    }

    /**
     * @test
     */
    public function it_gets_the_messages_bcc_email_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['bcc']));
        $this->assertEquals('foo', $arr['bcc'][0]);
    }

    /**
     * @test
     */
    public function it_gets_the_messages_from_email_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['from']));
        $this->assertEquals('foo', $arr['from'][0]);
    }

    /**
     * @test
     */
    public function it_gets_the_messages_to_email_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['to']));
        $this->assertEquals('foo', $arr['to'][0]);
    }

    /**
     * @test
     */
    public function it_gets_the_html_body_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['htmlBody']));
        $body = $arr['htmlBody'];
        $this->assertEquals('<b>foo</b>', $body);
    }

    /**
     * @test
     */
    public function it_gets_the_text_body_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);

        $this->assertTrue(isset($arr['textBody']));
        $body = $arr['textBody'];
        $this->assertEquals('foo', $body);
    }

    /**
     * @test
     */
    public function it_gets_the_date_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['date']));
        $this->assertEquals('2015-12-28', $arr['date']);
    }

    /**
     * @test
     */
    public function it_gets_the_important_flag_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['important']));
        $this->assertTrue($arr['important']);
    }

    /**
     * @test
     */
    public function it_gets_the_nessage_number_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['messageNum']));
        $this->assertEquals(1, $arr['messageNum']);
    }

    /**
     * @test
     */
    public function it_gets_size_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['size']));
        $this->assertEquals(1000, $arr['size']);
    }

    /**
     * @test
     */
    public function it_gets_the_uid_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['uid']));
        $this->assertEquals('foo', $arr['uid']);
    }

    /*
     * @test
     */
    public function it_gets_the_attachments_when_converted_to_json()
    {
        $message = $this->getMessage();
        $json = json_encode($message);
        $arr = json_decode($json, true);
        $this->assertTrue(isset($arr['attachments']));
        $this->assertEquals('foo', $arr['attachments'][0]);
    }

    /**
     * Creates a Message object
     * @return ImapMessage
     */
    protected function getMessage()
    {
        $attachments = m::mock('Humps\MailManager\Collections\ImapAttachmentCollection');
        $attachments->shouldReceive(['jsonSerialize' => ['foo']]);

        $emails = m::mock('Humps\MailManager\Collections\EmailCollection');
        $emails->shouldReceive(['jsonSerialize' => ['foo']]);

        $m = new ImapMessage();
        $m->setRead(true);
        $m->setAnswered(true);
        $m->setAttachments($attachments);
        $m->setBcc($emails);
        $m->setCc($emails);
        $m->setFrom($emails);
        $m->setTo($emails);
        $m->setHtmlBody('<b>foo</b>');
        $m->setTextBody('foo');
        $m->setDate('2015-12-28');
        $m->setImportant(true);
        $m->setMessageNum(1);
        $m->setSize(1000);
        $m->setSubject('foo');
        $m->setUid('foo');

        return $m;
    }
}
