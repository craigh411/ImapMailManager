<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 11/01/16
 * Time: 10:46
 */

namespace Humps\MailManager\Tests;

use Humps\MailManager\Contracts\Imap;
use Humps\MailManager\ImapConnection;
use Humps\MailManager\Components\Mailbox;
use Mockery as m;
use Humps\MailManager\ImapMailboxService;

class MailboxServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $mailbox;
    protected $connection;

    /**
     * @test
     */
    public function it_loads_the_aliases_file()
    {
        $mailboxManager = new ImapMailboxService($this->getImap(), __DIR__ . '/imap_config/aliases.php');
        $this->assertEquals(['trash' => 'INBOX.Trash', 'drafts' => 'INBOX.Drafts'], $mailboxManager->getAliases());
    }

    /**
     * @test
     */
    public function it_gets_the_folder_by_alias()
    {
        $mailboxManager = new ImapMailboxService($this->getImap(), __DIR__ . '/imap_config/aliases.php');
        $this->assertEquals('INBOX.Trash', $mailboxManager->getFolderByAlias('trash'));
    }

    /**
     * @test
     */
    public function it_gets_all_messages()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ALL', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getAllMessages());
    }

    /**
     * @test
     */
    public function it_gets_all_messages_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ALL', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getAllMessages(SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_all_messages_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ALL', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getAllMessages(SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ALL', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getAllMessages(SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_gets_all_unread_messages()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('UNSEEN', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getUnreadMessages());
    }

    /**
     * @test
     */
    public function it_gets_all__unread_messages_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('UNSEEN', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getUnreadMessages(SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_all_unread_messages_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('UNSEEN', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getUnreadMessages(SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_all_unread_messages_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('UNSEEN', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getUnreadMessages(SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_sender_passed_as_string()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FROM "foo@bar.com"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySender('foo@bar.com'));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_sender_passed_as_array()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FROM "foo@bar.com"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySender(['foo@bar.com']));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_multiple_senders()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FROM "foo@bar.com" FROM "bar@baz.com"', SORTDATE, true, FT_PEEK)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySender(['foo@bar.com', 'bar@baz.com']));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_sender_and_orders_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FROM "foo@bar.com"', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySender('foo@bar.com', SORTSIZE));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_sender_and_orders_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FROM "foo@bar.com"', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySender('foo@bar.com', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_sender_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FROM "foo@bar.com"', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySender('foo@bar.com', SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_subject_passed_as_string()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SUBJECT "subject 1"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySubject('subject 1'));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_subject_passed_as_array()
    {
        $imap = $this->getImap();
        $mailboxManager = new ImapMailboxService($imap);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SUBJECT "subject 1"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);


        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySubject(['subject 1']));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_multiple_subjects()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SUBJECT "subject 1" SUBJECT "subject 2"', SORTDATE, true, FT_PEEK)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySubject(['subject 1', 'subject 2']));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_subject_and_orders_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SUBJECT "subject 1"', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySubject('subject 1', SORTSIZE));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_subject_and_orders_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SUBJECT "subject 1"', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySubject('subject 1', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_subject_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SUBJECT "subject 1"', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBySubject('subject 1', SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_cc_passed_as_string()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('CC "foo@bar.com"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByCC('foo@bar.com'));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_cc_passed_as_array()
    {
        $imap = $this->getImap();
        $mailboxManager = new ImapMailboxService($imap);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('CC "foo@bar.com"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);


        $this->assertEquals([1, 2], $mailboxManager->getMessagesByCC(['foo@bar.com']));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_multiple_ccs()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('CC "foo@bar.com" CC "bar@baz.com"', SORTDATE, true, FT_PEEK)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByCC(['foo@bar.com', 'bar@baz.com']));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_cc_and_orders_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('CC "foo@bar.com"', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByCC('foo@bar.com', SORTSIZE));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_cc_and_orders_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('CC "foo@bar.com"', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByCC('foo@bar.com', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_cc_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('CC "foo@bar.com"', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByCC('foo@bar.com', SORTDATE, true, false));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_bcc_passed_as_string()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BCC "foo@bar.com"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByBCC('foo@bar.com'));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_bcc_passed_as_array()
    {
        $imap = $this->getImap();
        $mailboxManager = new ImapMailboxService($imap);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BCC "foo@bar.com"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);


        $this->assertEquals([1, 2], $mailboxManager->getMessagesByBCC(['foo@bar.com']));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_multiple_bccs()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BCC "foo@bar.com" BCC "bar@baz.com"', SORTDATE, true, FT_PEEK)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByBCC(['foo@bar.com', 'bar@baz.com']));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_bcc_and_orders_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BCC "foo@bar.com"', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByBCC('foo@bar.com', SORTSIZE));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_bcc_and_orders_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BCC "foo@bar.com"', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByBCC('foo@bar.com', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_bcc_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BCC "foo@bar.com"', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByBCC('foo@bar.com', SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_receiver_passed_as_string()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('TO "foo@bar.com"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByReceiver('foo@bar.com'));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_receiver_passed_as_array()
    {
        $imap = $this->getImap();
        $mailboxManager = new ImapMailboxService($imap);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('TO "foo@bar.com"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);


        $this->assertEquals([1, 2], $mailboxManager->getMessagesByReceiver(['foo@bar.com']));
    }


    /**
     * @test
     */
    public function it_gets_the_messages_by_multiple_receivers()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('TO "foo@bar.com" TO "bar@baz.com"', SORTDATE, true, FT_PEEK)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByReceiver(['foo@bar.com', 'bar@baz.com']));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_receiver_and_orders_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('TO "foo@bar.com"', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByReceiver('foo@bar.com', SORTSIZE));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_receiver_and_orders_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('TO "foo@bar.com"', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByReceiver('foo@bar.com', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_the_messages_by_receiver_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('TO "foo@bar.com"', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesByReceiver('foo@bar.com', SORTDATE, true, false));
    }


    /**
     * @test
     */
    public function it_gets_all_messages_on_the_given_date()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ON "11-Jan-2016"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesOn('2016-01-11'));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_on_the_given_date_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ON "11-Jan-2016"', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesOn('2016-01-11', SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_all_messages_on_the_given_date_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ON "11-Jan-2016"', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);


        $this->assertEquals([1, 2], $mailboxManager->getMessagesOn('2016-01-11', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_on_the_given_date_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ON "11-Jan-2016"', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesOn('2016-01-11', SORTDATE, true, false));
    }


    /**
     * @test
     */
    public function it_gets_all_messages_before_the_given_date()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BEFORE "11-Jan-2016"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBefore('2016-01-11'));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_before_the_given_date_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BEFORE "11-Jan-2016"', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBefore('2016-01-11', SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_all_messages_before_the_given_date_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BEFORE "11-Jan-2016"', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);


        $this->assertEquals([1, 2], $mailboxManager->getMessagesBefore('2016-01-11', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_before_the_given_date_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('BEFORE "11-Jan-2016"', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBefore('2016-01-11', SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_after_the_given_date()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SINCE "11-Jan-2016"', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesAfter('2016-01-11'));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_after_the_given_date_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SINCE "11-Jan-2016"', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesAfter('2016-01-11', SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_all_messages_after_the_given_date_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SINCE "11-Jan-2016"', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);


        $this->assertEquals([1, 2], $mailboxManager->getMessagesAfter('2016-01-11', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_after_the_given_date_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SINCE "11-Jan-2016"', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesAfter('2016-01-11', SORTDATE, true, false));
    }


    /**
     * @test
     */
    public function it_gets_all_messages_between_the_given_dates()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SINCE "01-Jan-2016" BEFORE "12-Jan-2016"', SORTDATE, true, FT_PEEK)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBetween('2016-01-01', '2016-01-11'));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_between_the_given_dates_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SINCE "01-Jan-2016" BEFORE "12-Jan-2016"', SORTSIZE, true, FT_PEEK)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getMessagesBetween('2016-01-01', '2016-01-11', SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_all_messages_between_the_given_dates_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SINCE "01-Jan-2016" BEFORE "12-Jan-2016"', SORTSIZE, false, FT_PEEK)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);


        $this->assertEquals([1, 2], $mailboxManager->getMessagesBetween('2016-01-01', '2016-01-11', SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_all_messages_between_the_given_dates_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SINCE "01-Jan-2016" BEFORE "12-Jan-2016"', SORTDATE, true, 0)->andReturn([
            1,
            2
        ]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([
            1,
            2
        ], $mailboxManager->getMessagesBetween('2016-01-01', '2016-01-11', SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_throws_an_error_if_the_to_date_is_less_then_the_from_date()
    {
        $imap = $this->getImap();
        $mailboxManager = new ImapMailboxService($imap);

        $this->setExpectedException('Exception');
        $this->assertEquals([1, 2], $mailboxManager->getMessagesBetween('2016-01-11', '2016-01-01'));
    }

    /**
     * @test
     */
    public function it_gets_read_messages()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SEEN', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getReadMessages());
    }

    /**
     * @test
     */
    public function it_gets_read_messages_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SEEN', SORTSIZE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getReadMessages(SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_read_messages_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('SEEN', SORTSIZE, false, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getReadMessages(SORTSIZE, false));
    }


    /**
     * @test
     */
    public function it_gets_important_messages()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FLAGGED', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getImportantMessages());
    }

    /**
     * @test
     */
    public function it_gets_important_messages_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FLAGGED', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getImportantMessages(SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_important_messages_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FLAGGED', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getImportantMessages(SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_important_messages_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('FLAGGED', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getImportantMessages(SORTDATE, true, false));
    }


    /**
     * @test
     */
    public function it_gets_answered_messages()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ANSWERED', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getAnsweredMessages());
    }

    /**
     * @test
     */
    public function it_gets_answered_messages_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ANSWERED', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getAnsweredMessages(SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_answered_messages_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ANSWERED', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getAnsweredMessages(SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_answered_messages_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('ANSWERED', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getAnsweredMessages(SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_gets_unanswered_messages()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('UNANSWERED', SORTDATE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getUnansweredMessages());
    }

    /**
     * @test
     */
    public function it_gets_unanswered_messages_and_sorts_them_by_descending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('UNANSWERED', SORTSIZE, true, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getUnansweredMessages(SORTSIZE));
    }


    /**
     * @test
     */
    public function it_gets_unanswered_messages_and_sorts_them_by_ascending_size()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('UNANSWERED', SORTSIZE, false, FT_PEEK)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getUnansweredMessages(SORTSIZE, false));
    }

    /**
     * @test
     */
    public function it_gets_unanswered_messages_and_sets_them_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('sort')->with('UNANSWERED', SORTDATE, true, 0)->andReturn([1, 2]);
        $mailboxManager = new ImapMailboxService($imap);

        $this->assertEquals([1, 2], $mailboxManager->getUnansweredMessages(SORTDATE, true, false));
    }

    /**
     * @test
     */
    public function it_gets_a_comma_delimited_message_list()
    {
        $messageList = ImapMailboxService::getMessageList([1, 2, 3, 4, 5]);
        $this->assertEquals('1,2,3,4,5', $messageList);
    }

    /**
     * @test
     */
    public function it_flags_the_messages_as_read()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('setFlag')->with('1,2', '\Seen')->andReturn(true);
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->flagAsRead('1,2');
    }

    /**
     * @test
     */
    public function it_flags_the_messages_as_important()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('setFlag')->with('1,2', '\Flagged')->andReturn(true);
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->flagAsImportant('1,2');
    }

    /**
     * @test
     */
    public function it_flags_the_messages_as_answered()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('setFlag')->with('1,2', '\Answered')->andReturn(true);
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->flagAsAnswered('1,2');
    }

    /**
     * @test
     */
    public function it_gets_the_unread_message_count()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getStatus')->with(SA_UNSEEN)->andReturn((object)['unseen' => 5]);
        $mailboxManager = new ImapMailboxService($imap);
        $this->assertEquals(5, $mailboxManager->getUnreadMessageCount());
    }

    /**
     * @test
     */
    public function it_opens_a_new_folder_by_alias()
    {


        $imap = $this->getImap();
        $this->connection->shouldReceive('refresh')->andReturn(true);

        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('refresh')->andReturn(true);
        $mailboxManager = new ImapMailboxService($imap, __DIR__ . '/imap_config/aliases.php');
        $opened = $mailboxManager->openFolder('trash');
        /** @noinspection PhpUndefinedMethodInspection */
        $this->connection->shouldHaveReceived('refresh')->once();

        $this->assertTrue($opened);
    }

    /**
     * @test
     */
    public function it_opens_a_new_folder_without_an_alias()
    {
        $imap = $this->getImap();
        $this->connection->shouldReceive('refresh')->andReturn(true);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mailbox->shouldReceive('setFolder')->with('foo');

        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getMailbox')->andReturn($this->mailbox);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('refresh')->andReturn(true);

        $mailboxManager = new ImapMailboxService($imap);
        $opened = $mailboxManager->openFolder('foo');

        /** @noinspection PhpUndefinedMethodInspection */
        $this->connection->shouldHaveReceived('refresh')->once();

        $this->assertTrue($opened);
    }


    /**
     * @test
     */
    public function it_deletes_all_messages_from_the_given_mailbox()
    {

        $imap = $this->getImap();
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mailbox->shouldReceive('setFolder')->once()->with('FOO');
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mailbox->shouldReceive('setFolder')->once()->with('INBOX');

        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getFolder')->andReturn('INBOX');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('deleteMessages')->andReturn(true);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('setMessagesForDeletion')->with('1:*')->andReturn(true);


        /** @noinspection PhpUndefinedMethodInspection */
        $this->connection->shouldReceive('refresh')->andReturn(true);

        $mailboxManager = new ImapMailboxService($imap);
        $deleted = $mailboxManager->deleteAllMessages('FOO');

        $this->assertTrue($deleted);

        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldHaveReceived('deleteMessages')->once();
        $this->mailbox->shouldHaveReceived('setFolder')->twice();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldNotHaveReceived('close');
    }

    /**
     * @test
     */
    public function it_deletes_all_messages_from_the_trash_mailbox()
    {
        $imap = $this->getImap();
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mailbox->shouldReceive('setFolder')->once()->with('INBOX.Trash');
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mailbox->shouldReceive('setFolder')->once()->with('INBOX');

        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getFolder')->andReturn('INBOX');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('deleteMessages')->andReturn(true);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('setMessagesForDeletion')->with('1:*')->andReturn(true);


        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getMailbox')->andReturn($this->mailbox);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->connection->shouldReceive('refresh')->andReturn(true);

        $mailboxManager = new ImapMailboxService($imap, __DIR__ . '/imap_config/aliases.php');
        $deleted = $mailboxManager->emptyTrash();

        $this->assertTrue($deleted);

        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldHaveReceived('deleteMessages')->once();
        $this->mailbox->shouldHaveReceived('setFolder')->twice();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldNotHaveReceived('close');
    }

    /**
     * This test is for an edge case, where there is an issue with the server
     * during the delete operation
     * @test
     */
    public function it_should_fail_to_reopen_the_initial_folder_after_deletion()
    {
        $imap = $this->getImap();
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mailbox->shouldReceive('setFolder')->once()->with('INBOX.Trash');
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mailbox->shouldReceive('setFolder')->once()->with('INBOX');

        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getFolder')->andReturn('INBOX');

        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('deleteMessages')->andReturn(true);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('setMessagesForDeletion')->with('1:*')->andReturn(true);
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getMailbox')->andReturn($this->mailbox);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->connection->shouldReceive('closeConnection');
        /** @noinspection PhpUndefinedMethodInspection */
        $this->connection->shouldReceive('refresh')->once()->andReturn(true);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->connection->shouldReceive('refresh')->once()->andReturn(false);

        $this->setExpectedException('Exception', 'Unable to re-open folder INBOX Connection has been closed');
        $mailboxManager = new ImapMailboxService($imap, __DIR__ . '/imap_config/aliases.php');
        $mailboxManager->deleteAllMessages('trash');
    }

    /**
     * @test
     */
    public function it_should_get_all_the_folders_in_the_current_mailbox()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getFolders')->with('{imap.example.com:993/imap/ssl}', '*');
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->getAllFolders();
    }

    /**
     * @test
     */
    public function it_should_get_all_child_folders_in_the_given_folder_by_alias()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getFolders')->with('{imap.example.com:993/imap/ssl}INBOX.Trash', '*');
        $mailboxManager = new ImapMailboxService($imap, __DIR__ . '/imap_config/aliases.php');
        $mailboxManager->getChildFolders('trash');
    }


    /**
     * @test
     */
    public function it_should_get_all_child_folders_in_the_given_folder_with_no_alias()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getFolders')->with('{imap.example.com:993/imap/ssl}FOO', '*');
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->getChildFolders('FOO');
    }

    /**
     * @test
     */
    public function it_should_move_messages_to_the_given_folder()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('moveMessages')->with('1,2', 'Starred')->andReturn(true);
        $mailboxManager = new ImapMailboxService($imap);
        $this->assertTrue($mailboxManager->moveMessages('1,2', 'Starred'));
    }

    /**
     * @test
     */
    public function it_should_move_messages_to_the_trash_folder()
    {
        $imap = $this->getImap();
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('moveMessages')->with('1,2', 'INBOX.Trash')->andReturn(true);
        $mailboxManager = new ImapMailboxService($imap, __DIR__ . '/imap_config/aliases.php');
        $this->assertTrue($mailboxManager->moveToTrash('1,2'));
    }

    /**
     * @test
     */
    public function it_should_create_a_new_top_level_folder()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('createMailbox')->with('{imap.example.com:993/imap/ssl}FOO');
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->createFolder('FOO');
    }

    /**
     * @test
     */
    public function it_should_create_a_new_child_folder_for_an_aliased_folder()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('createMailbox')->with('{imap.example.com:993/imap/ssl}INBOX.Drafts.FOO');
        $mailboxManager = new ImapMailboxService($imap, __DIR__ . '/imap_config/aliases.php');
        $mailboxManager->createFolder('FOO', 'drafts');
    }

    /**
     * @test
     */
    public function it_should_create_a_new_child_folder_for_a_non_aliased_folder()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('createMailbox')->with('{imap.example.com:993/imap/ssl}FOO.BAR');
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->createFolder('BAR', 'FOO');
    }

    /**
     * @test
     */
    public function it_should_create_a_new_child_folder_with_a_slash_delimiter()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('createMailbox')->with('{imap.example.com:993/imap/ssl}FOO/BAR');
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->createFolder('BAR', 'FOO', '/');
    }

    /**
     * @test
     */
    public function it_should_delete_a_top_level_folder()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('deleteMailbox')->with('{imap.example.com:993/imap/ssl}FOO');
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->deleteFolder('FOO');
    }

    /**
     * @test
     */
    public function it_should_delete_a_child_folder_for_an_aliased_folder()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('deleteMailbox')->with('{imap.example.com:993/imap/ssl}INBOX.Drafts.FOO');
        $mailboxManager = new ImapMailboxService($imap, __DIR__ . '/imap_config/aliases.php');
        $mailboxManager->deleteFolder('FOO', 'drafts');
    }

    /**
     * @test
     */
    public function it_should_delete_a_child_folder_for_a_non_aliased_folder()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('deleteMailbox')->with('{imap.example.com:993/imap/ssl}FOO.BAR');
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->deleteFolder('BAR', 'FOO');
    }

    /**
     * @test
     */
    public function it_should_delete_a_child_folder_with_a_slash_delimiter()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('deleteMailbox')->with('{imap.example.com:993/imap/ssl}FOO/BAR');
        $mailboxManager = new ImapMailboxService($imap);
        $mailboxManager->deleteFolder('BAR', 'FOO', '/');
    }


    /**
     * @test
     */
    public function it_returns_false_when_folder_does_not_exist()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getFolders')->with('{imap.example.com:993/imap/ssl}', 'FOO')->andReturn('');

        $mailboxManager = new ImapMailboxService($imap);
        $exists = $mailboxManager->folderExists('FOO');
        $this->assertFalse($exists);
    }

    /**
     * @test
     */
    public function it_returns_true_when_top_level_folder_does_exist()
    {
        $imap = $this->getImap(false);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->mailbox->shouldReceive('getMailboxName')->with(true)->andReturn('{imap.example.com:993/imap/ssl}');
        /** @noinspection PhpUndefinedMethodInspection */
        $imap->shouldReceive('getFolders')->with('{imap.example.com:993/imap/ssl}', 'FOO')->andReturn([]);

        $mailboxManager = new ImapMailboxService($imap);
        $exists = $mailboxManager->folderExists('FOO');
        $this->assertTrue($exists);
    }


    /**
     * @return Imap
     */
    protected function getImap($setMailboxDefaults = true, $setConnectionDefaults = true)
    {
        $this->mailbox = m::mock(Mailbox::class);
        if ($setMailboxDefaults) {
            /** @noinspection PhpMethodParametersCountMismatchInspection */
            $this->mailbox->shouldReceive('setFolder')->with('INBOX.Trash');
            $this->mailbox->shouldReceive('getMailboxName');
        }

        $this->connection = m::mock(ImapConnection::class);
        if ($setConnectionDefaults) {
            $this->connection->shouldReceive('getMailbox')->andReturn($this->mailbox);
        }

        $imap = m::mock('Humps\MailManager\Contracts\Imap');
        $imap->shouldReceive('getConnection')->andReturn($this->connection);
        $imap->shouldReceive('getMailbox')->andReturn($this->mailbox);

        /**
         * @var Imap $imap
         */
        return $imap;
    }
}
