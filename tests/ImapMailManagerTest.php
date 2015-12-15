<?php
namespace Humps\MailManager\Tests;

use Humps\MailManager\ImapMailManager;

use Faker;
use PHPUnit_Framework_TestCase;


class ImapMailManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ImapMailManager
     */
    protected $mailManager;
    protected $faker;
    protected $createdEmails;

    public function setUp()
    {
        $this->faker = Faker\Factory::create();
        $this->mailManager = new ImapMailManager();
        $this->createdEmails = [];
        $this->createEmail();
        $this->mailManager->refresh();
    }

    public function tearDown()
    {
        $this->mailManager->openFolder('inbox');
        $messages = $this->mailManager->searchMessages('subject', $this->createdEmails);
        // Delete all created messaged
        foreach ($messages as $message) {
            $this->mailManager->deleteMessages($message->getMessageNo());
        }

        $this->mailManager->closeConnection();
    }


    /**
     * @test
     */
    public function it_should_get_the_messages_from_the_mailbox()
    {
        $this->assertTrue(count($this->mailManager->getAllMessages()) === $this->mailManager->getMessageCount());
        $this->assertInstanceOf('Humps\MailManager\Contracts\Message', $this->mailManager->getAllMessages()[0]);
    }


    /**
     * @test
     */
    public function it_flags_the_messages_as_read()
    {
        $this->createEmail();
        $this->mailManager->refresh();

        $messages = $this->mailManager->searchMessages('subject', $this->createdEmails);

        $messageList = ImapMailManager::getMessageList($messages);
        $this->mailManager->flagAsRead($messageList);

        $this->assertEquals(2, $this->mailManager->getMessageCount());
        $this->assertEquals(0, $this->mailManager->getUnreadMessageCount());
    }

    /**
     * @test
     */
    public function it_returns_the_number_of_unread_messages()
    {
        $this->assertEquals(1, $this->mailManager->getMessageCount());
    }

    /**
     * @test
     */
    public function it_returns_a_list_of_folder_names()
    {
        $folders = $this->mailManager->getAllFolders();

        $this->assertInstanceOf('Humps\MailManager\Folder', $folders[0]);
    }

    /**
     * @test
     */
    public function it_should_connect_to_the_mailbox()
    {
        $this->assertNotFalse($this->mailManager->getConnection());
    }


    /**
     * @test
     */
    public function it_should_get_emails_by_email_address()
    {
        $email = $this->faker->email;

        $this->createEmail($email);
        $this->createEmail($email);

        // Re-connect so we can get the new messages
        $this->mailManager->refresh();
        $messages = $this->mailManager->getMessagesBySender($email);

        $this->assertEquals(2, count($messages));
        $this->assertInstanceOf('Humps\MailManager\Contracts\Message', $messages[0]);
    }

    /**
     * @test
     */
    public function it_should_change_to_the_trash_folder()
    {
        $this->mailManager->openFolder('trash');
        $this->assertContains('[Gmail]/Trash', $this->mailManager->getMailboxName());
    }

    /**
     * @test
     */
    public function it_should_move_messages_to_the_trash_folder()
    {
        $this->createEmail();
        $this->mailManager->refresh();

        $messages = $this->mailManager->searchMessages('subject', $this->createdEmails);

        $messageList = ImapMailManager::getMessageList($messages);
        $this->mailManager->moveToTrash($messageList, 'trash');

        $this->mailManager->openFolder('trash');
        $this->assertEquals(2, $this->mailManager->getMessageCount());
    }

    /**
     * @test
     */
    public function it_should_delete_messages_from_the_trash_folder()
    {
        $this->mailManager->openFolder('trash');
        $this->assertTrue($this->mailManager->getMessageCount() > 0);
        $this->mailManager->emptyTrash();
        $this->assertEquals(0, $this->mailManager->getMessageCount());
    }

    /**
     * @test
     */
    public function it_should_get_the_number_of_message_in_the_inbox()
    {
        $this->assertInternalType('int', $this->mailManager->getMessageCount());
    }


    /**
     * Creates an E-mail for the account
     */
    protected function createEmail($from = 'test@test.com')
    {
        $subject = "Test " . $this->faker->text(20);
        $this->createdEmails[] = $subject;

        $envelope["to"] = "foo@test.com";
        $envelope["subject"] = $subject;
        $envelope["from"] = $from;

        $part["type"] = TYPETEXT;
        $part["subtype"] = "plain";
        $part["description"] = "part description";
        $part["contents.data"] = "Testing Content";

        $body[1] = $part;

        $msg = imap_mail_compose($envelope, $body);

        if (imap_append($this->mailManager->getConnection(), $this->mailManager->getMailboxName(), $msg) === false) {
            die("could not append message: " . imap_last_error());
        }
    }

}
