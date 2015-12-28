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
    protected static $mailManager;
    /**
     * @var Faker
     */
    protected $faker;
    protected $createdEmails;


    public static function setupBeforeClass()
    {
        self::$mailManager = new ImapMailManager();
    }

    public static function tearDownAfterClass()
    {
        self::$mailManager->closeConnection();
    }

    public function setUp()
    {
        $this->faker = Faker\Factory::create();

        $this->createdEmails = [];
        $this->createEmail();
        self::$mailManager->refresh();
    }

    public function tearDown()
    {
        self::$mailManager->openFolder('inbox');
        $messages = self::$mailManager->searchMessages('subject', $this->createdEmails);
        // Delete all created messaged
        foreach ($messages as $message) {
            self::$mailManager->deleteMessages($message->getMessageNo());
        }

        self::$mailManager->closeConnection();
    }


    /**
     * @test
     */
    public function it_should_get_the_messages_from_the_mailbox()
    {
        $this->assertTrue(count(self::$mailManager->getAllMessages()) === self::$mailManager->getMessageCount());
        $this->assertInstanceOf('Humps\MailManager\Contracts\Message', self::$mailManager->getAllMessages()[0]);
    }


    /**
     * @test
     */
    public function it_flags_the_messages_as_read()
    {
        $this->createEmail();
        self::$mailManager->refresh();

        $messages = self::$mailManager->searchMessages('subject', $this->createdEmails);

        $messageList = ImapMailManager::getMessageList($messages);
        self::$mailManager->flagAsRead($messageList);

        $this->assertEquals(2, self::$mailManager->getMessageCount());
        $this->assertEquals(0, self::$mailManager->getUnreadMessageCount());
    }

    /**
     * @test
     */
    public function it_returns_the_number_of_unread_messages()
    {
        $this->assertEquals(1, self::$mailManager->getMessageCount());
    }

    /**
     * @test
     */
    public function it_returns_a_list_of_folder_names()
    {
        $folders = self::$mailManager->getAllFolders();

        $this->assertInstanceOf('Humps\MailManager\Folder', $folders[0]);
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
        self::$mailManager->refresh();
        $messages = self::$mailManager->getMessagesBySender($email);

        $this->assertEquals(2, count($messages));
        $this->assertInstanceOf('Humps\MailManager\Contracts\Message', $messages[0]);
    }

    /**
     * @test
     */
    public function it_should_change_to_the_trash_folder()
    {
        self::$mailManager->openFolder('trash');
        $this->assertContains('[Gmail]/Trash', self::$mailManager->getMailboxName());
    }

    /**
     * @test
     */
    public function it_should_move_messages_to_the_trash_folder()
    {
        $this->createEmail();
        self::$mailManager->refresh();

        $messages = self::$mailManager->searchMessages('subject', $this->createdEmails);

        $messageList = ImapMailManager::getMessageList($messages);
        self::$mailManager->moveToTrash($messageList, 'trash');

        self::$mailManager->openFolder('trash');
        $this->assertEquals(2, self::$mailManager->getMessageCount());
    }

    /**
     * @test
     */
    public function it_should_delete_messages_from_the_trash_folder()
    {
        self::$mailManager->openFolder('trash');
        $this->assertTrue(self::$mailManager->getMessageCount() > 0);
        self::$mailManager->emptyTrash();
        $this->assertEquals(0, self::$mailManager->getMessageCount());
    }

    /**
     * @test
     */
    public function it_should_get_the_number_of_message_in_the_inbox()
    {
        $this->assertInternalType('int', self::$mailManager->getMessageCount());
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

        if (imap_append(self::$mailManager->getConnection(), self::$mailManager->getMailboxName(), $msg) === false) {
            die("could not append message: " . imap_last_error());
        }
    }

}
