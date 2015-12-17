<?php
use Humps\MailManager\ImapMailManager;

require_once '../vendor/autoload.php';

$folder = (isset($_REQUEST['folder'])) ? $_REQUEST['folder'] : 'INBOX';

$mailManager = new ImapMailManager($folder);
$messages = $mailManager->getMessagesAfter('17-12-2015', true);
$folders = $mailManager->getAllFolders();
?>

<html>
<head>
    <title>Emails</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
            integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
            crossorigin="anonymous"></script>

    <style type="text/css">
        body {
            width: 98%;
            margin: auto;
            margin-top: 20px;
        }

        .table {
            font-size: 0.9em;
        }

        .mailboxes {
            padding: 15px;
        }

        .folders {
            list-style: none;
            padding-left: 5px;
        }

        .folders {
            list-style: none;
            padding-left: 5px;
        }

        .empty {
            text-align: center;
            font-weight: bold;
        }

        .folders li {
            padding-bottom: 10px;
        }

        .folder-glyph {
            margin-right: 7px;
        }

        .unread {
            font-weight: bold;
        }
    </style>

</head>
<body>

<div class="col-md-12">
    <h2><?= $mailManager->getMailbox()->getFolder() ?></h2>
    <div class="panel panel-default pull-left col-md-2 mailboxes ">
        <ul class=" folders">
            <? if (count($folders)): ?>
                <? foreach ($folders as $folder): ?>
                    <li><span class="glyphicon glyphicon-inbox folder-glyph"></span><a
                            href="example.php?folder=<?= $folder->getName() ?>"><?= $folder->getName() ?></a></li>
                <? endforeach; ?>

            <? endif; ?>
        </ul>
    </div>


    <div class="col-md-9">
        <table class="table table-striped table-bordered table-hover pull-right ">
            <tr>
                <th>From</th>
                <th>Subject</th>
                <th>Date</th>
            </tr>
            <tbody>
            <?
            if (count($messages)):
                foreach ($messages as $i => $message):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($message->getFrom()) ?></td>
                        <td class="<?= ($message->isUnread()) ? 'unread' : '' ?>">
                            <a href="showMessage.php?mid=<?= $message->getMessageNo() ?>&folder=<?=$mailManager->getFolderName()?>"><?= $message->getSubject() ?></a>
                        </td>
                        <td><?= $message->getDate()->diffForHumans(); ?></td>
                    </tr>
                    <?
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="3" class="empty">Mailbox is Empty!</td>
                </tr>
                <?
            endif;
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
