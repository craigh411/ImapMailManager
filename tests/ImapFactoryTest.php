<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 25/01/16
 * Time: 11:23
 */

namespace Humps\MailManager\Tests;


use Humps\MailManager\Tests\Helpers\ImapFactoryHelper;

class ImapFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_creates_an_Imap_object()
    {
        $imap = ImapFactoryHelper::create('INBOX');
        $this->assertInstanceOf('Humps\MailManager\Contracts\Imap', $imap);
    }
}
