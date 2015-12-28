<?php
set_time_limit(0);
$time_start = microtime(true);


use Carbon\Carbon;
use Humps\MailManager\ImapMailManager;

require_once '../vendor/autoload.php';

$folder = (isset($_REQUEST['folder'])) ? $_REQUEST['folder'] : 'INBOX';

$mailManager = new ImapMailManager($folder);
$messages = $mailManager->getMessagesAfter('2015-12-01', true);
//$messages = $mailManager->searchMessages('FROM', 'Joyce Li');
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
        html, body {
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


    <div class="col-md-10 pull-right">
        <table class="table table-striped table-bordered table-hover  ">
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
                        <td><?= ($message->getFrom()->get(0)->getPersonal()) ? $message->getFrom()->get(0)->getPersonal() : htmlspecialchars($message->getFrom()->get(0)->getEmailAddress()) ?></td>
                        <td class="<?= (!$message->isRead()) ? 'unread' : '' ?>">
                            <a href="showMessage.php?mid=<?= $message->getMessageNo() ?>&folder=<?= $mailManager->getFolderName() ?>"><?= $message->getSubject() ?> </a>
                        </td>
                        <td><span
                                class="glyphicon glyphicon-paperclip" <?= (!$message->hasAttachments()) ? 'style="visibility:hidden;"' : '' ?>></span>
                            <?= $message->getDate()->diffForHumans(); ?></td>
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
<?
// Script end
$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
?>
