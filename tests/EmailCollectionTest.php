<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 18/01/16
 * Time: 14:42
 */

namespace Humps\MailManager\Tests;

use Mockery as m;
use Humps\MailManager\Collections\EmailCollection;

class EmailCollectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_throws_an_error_when_a_non_EmailAddress_Collectable_is_added_by_index()
    {
        $this->setExpectedException('InvalidArgumentException');
        $collectable = m::mock('Humps\MailManager\Collections\Contracts\Collectable');
        $emailCollection = new EmailCollection();
        $emailCollection[] = $collectable;
    }

    /**
     * @test
     */
    public function it_throws_adds_an_email_address_by_index()
    {
        $email = m::mock('Humps\MailManager\Components\EmailAddress');
        $emailCollection = new EmailCollection();
        $emailCollection[] = $email;
        $this->assertEquals(1, count($emailCollection));
    }

    /**
     * @test
     */
    public function it_creates_a_comma_delimited_list_of_emails()
    {
        $email1 = m::mock('Humps\MailManager\Components\EmailAddress');
        $email1->shouldReceive('getEmailAddress')->andReturn('foo@bar.com');
        $email2 = m::mock('Humps\MailManager\Components\EmailAddress');
        $email2->shouldReceive('getEmailAddress')->andReturn('bar@baz.com');

        $emailCollection = new EmailCollection();
        $emailCollection[0] = $email1;
        $emailCollection[1] = $email2;

        $this->assertEquals('foo@bar.com, bar@baz.com', $emailCollection->implodeEmails());
    }

    /**
     * @test
     */
    public function it_creates_a_slashed_delimited_list_of_emails()
    {
        $email1 = m::mock('Humps\MailManager\Components\EmailAddress');
        $email1->shouldReceive('getEmailAddress')->andReturn('foo@bar.com');
        $email2 = m::mock('Humps\MailManager\Components\EmailAddress');
        $email2->shouldReceive('getEmailAddress')->andReturn('bar@baz.com');

        $emailCollection = new EmailCollection();
        $emailCollection[0] = $email1;
        $emailCollection[1] = $email2;

        $this->assertEquals('foo@bar.com / bar@baz.com', $emailCollection->implodeEmails(' / '));
    }
}
