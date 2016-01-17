<?php
namespace Humps\MailManager\Tests\Helpers;

use Humps\MailManager\ImapHandler;
use Humps\MailManager\ImapMailManager;
use stdClass;

/**
 * A Helper class to mock the imap functionality return values.
 *
 * Class ImapMailManagerTestHelper
 * @package Humps\MailManager\Tests\Helpers
 */
class ImapMailManagerTestHelper
{

    protected $connection;




    /**
     * A mock for fetchStructure
     * @param int $messageNumber
     */
    public static function fetchStructure()
    {
        return (object)[
            'type'          => 1,
            'encoding'      => 0,
            'ifsubtype'     => 1,
            'subtype'       => 'MIXED',
            'ifdescription' => 0,
            'ifid'          => 0,
            'ifdisposition' => 0,
            'ifdparameters' => 0,
            'ifparameters'  => 1,
            'parameters'    => [
                0 => (object)[
                    'attribute' => 'BOUNDARY',
                    'value'     => '001a1143d97e2f7a4f0526c9b9b5',
                ],
            ],
            'parts'         => (object)[
                0 => (object)[
                    'type'          => 1,
                    'encoding'      => 0,
                    'ifsubtype'     => 1,
                    'subtype'       => 'ALTERNATIVE',
                    'ifdescription' => 0,
                    'ifid'          => 0,
                    'ifdisposition' => 0,
                    'ifdparameters' => 0,
                    'ifparameters'  => 1,
                    'parameters'    => [
                        0 => (object)[
                            'attribute' => 'BOUNDARY',
                            'value'     => '001a1143d97e2f7a3b0526c9b9b3',
                        ],
                    ],
                    'parts'         => [
                        0 => (object)[
                            'type'          => 0,
                            'encoding'      => 0,
                            'ifsubtype'     => 1,
                            'subtype'       => 'PLAIN',
                            'ifdescription' => 0,
                            'ifid'          => 0,
                            'lines'         => 1,
                            'bytes'         => 12,
                            'ifdisposition' => 0,
                            'ifdparameters' => 0,
                            'ifparameters'  => 1,
                            'parameters'    => [
                                0 => (object)[
                                    'attribute' => 'CHARSET',
                                    'value'     => 'UTF-8',
                                ],
                            ],
                        ],
                        1 => (object)[
                            'type'          => 0,
                            'encoding'      => 0,
                            'ifsubtype'     => 1,
                            'subtype'       => 'HTML',
                            'ifdescription' => 0,
                            'ifid'          => 0,
                            'lines'         => 1,
                            'bytes'         => 33,
                            'ifdisposition' => 0,
                            'ifdparameters' => 0,
                            'ifparameters'  => 1,
                            'parameters'    => [
                                0 => (object)[
                                    'attribute' => 'CHARSET',
                                    'value'     => 'UTF-8',
                                ],
                            ],
                        ],
                    ],
                ],
                1 => (object)[
                    'type'          => 5,
                    'encoding'      => 3,
                    'ifsubtype'     => 1,
                    'subtype'       => 'PNG',
                    'ifdescription' => 0,
                    'ifid'          => 0,
                    'bytes'         => 6560,
                    'ifdisposition' => 1,
                    'disposition'   => 'ATTACHMENT',
                    'ifdparameters' => 1,
                    'dparameters'   => [
                        0 => (object)[
                            'attribute' => 'FILENAME',
                            'value'     => 'apple.png',
                        ],
                    ],
                    'ifparameters'  => 1,
                    'parameters'    => [
                        0 => (object)[
                            'attribute' => 'NAME',
                            'value'     => 'apple.png',
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function fetchBody($part = 1)
    {
        // Lets mock getting body parts based on mocked fetchStructure
        switch ($part) {
            case 2:
                // If part 2 was passed this will return the base64 encoded image attachment
                return 'iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ bWFnZVJlYWR5ccllPAAAA2RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdp bj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6 eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEz NDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJo dHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlw dGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEu MC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVz b3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1N Ok9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDozODlCMUYyRDRDNzJERjExQTIxRDg0Mzk3RTFD QkJBOCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpENzc4QTM4MDcyQzgxMURGODZGNzlENTI0 MDU4NjIyQiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpENzc4QTM3RjcyQzgxMURGODZGNzlE NTI0MDU4NjIyQiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3Mi PiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDozODlCMUYyRDRD NzJERjExQTIxRDg0Mzk3RTFDQkJBOCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDozODlCMUYy RDRDNzJERjExQTIxRDg0Mzk3RTFDQkJBOCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRG 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 rkJggg==';
            default:
                // By default just return the PLAIN email
                return "TEST EMAIL\n\rfoo bar baz";
        }
    }

    /**
     * Mock imap_sort
     * @param null $criteria
     * @param null $sortBy
     * @param bool $reverse
     * @param int $options
     * @return array
     */
    public function sort($criteria = null, $sortBy = null, $reverse = true, $options = 0)
    {
        return [0, 1];
    }

    /**
     * A mock for retrieving headers. It's essentially a real emails headers dumped with var_export();
     * @param int $messageNumber
     */
    public static function getMessageHeaders()
    {
        return (object)[
            'date'            => 'Sun, 20 Dec 2015 16:05:20 +0000',
            'Date'            => 'Sun, 20 Dec 2015 16:05:20 +0000',
            'subject'         => 'test',
            'Subject'         => 'test',
            'message_id'      => '',
            'toaddress'       => 'foo@gmail.com',
            'to'              => [
                0 => (object)[
                    'mailbox' => 'foo',
                    'host'    => 'gmail.com',
                ],
            ],
            'fromaddress'     => 'Tom Jones ',
            'from'            => (object)[
                0 => (object)[
                    'personal' => 'Tom Jones',
                    'mailbox'  => 'foo',
                    'host'     => 'gmail.com',
                ],
            ],
            'reply_toaddress' => 'Tom Jones ',
            'reply_to'        => (object)[
                0 => (object)[
                    'personal' => 'Tom Jones',
                    'mailbox'  => 'foo',
                    'host'     => 'gmail.com',
                ],
            ],
            'senderaddress'   => 'Tom Jones ',
            'sender'          => (object)[
                0 => (object)[
                    'personal' => 'Tom Jones',
                    'mailbox'  => 'foo',
                    'host'     => 'gmail.com',
                ],
            ],
            'Recent'          => '0',
            'Unseen'          => '1',
            'Flagged'         => '0',
            'Answered'        => '0',
            'Deleted'         => '0',
            'Draft'           => '0',
            'Msgno'           => '2',
            'MailDate'        => '13-Dec-2015 16:05:20 +0000',
            'Size'            => '7553',
            'udate'           => 1450022720,
        ];
    }

    public function getMessageNumber($uid)
    {
        return 1;
    }

    /**
     * A mock for retrieving mailbox folders
     * @param $mailbox
     * @param string $pattern
     * @return array
     */
    public static function getMailboxFolders()
    {
        return [
            0 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}INBOX',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
            1 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}Keep',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
            2 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}[Gmail]',
                'attributes' => 34,
                'delimiter'  => '/',
            ],
            3 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}[Gmail]/All Mail',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
            4 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}[Gmail]/Drafts',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
            5 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}[Gmail]/Important',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
            6 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
            7 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}[Gmail]/Spam',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
            8 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}[Gmail]/Starred',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
            9 => (object)[
                'name'       => '{imap.gmail.com:993/imap/ssl}[Gmail]/Trash',
                'attributes' => 64,
                'delimiter'  => '/',
            ],
        ];
    }
}