<?
require_once '../vendor/autoload.php';

$mid = (isset($_REQUEST['mid'])) ? $_REQUEST['mid'] : null;
$folder = (isset($_REQUEST['folder'])) ? $_REQUEST['folder'] : 'INBOX';

// $mid = 9194;
?>

<html>
<head>
    <title></title>
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
            width: 90%;
            margin: auto;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table {
            border-bottom: 1px solid #ccc;
        }

        .message {
            border: 1px solid #ccc;
            border-top: 0;
            padding: 0;
        }

        .body {
            padding: 10px;
        }
    </style>
</head>
<body>
<div class="message">
    <?
    if (!is_null($mid)):
        $mailManager = new Humps\MailManager\ImapMailManager($folder);
        $message = $mailManager->getMessage($mid);
        ?>
        <table class="table table-striped">
            <tr>
                <td>From:</td>
                <td><?= htmlspecialchars($message->getFrom()) ?></td>
            </tr>
            <tr>
                <td>To:</td>
                <td><?= implode(',', array_map(function ($m) {
                        return $m->getEmailAddress();
                    }, $message->getTo()));

                    ?></td>
            </tr>
            <tr>
                <td>Subject:</td>
                <td><?= $message->getSubject() ?></td>
            </tr>
        </table>

        <div class="body">
            <?= $message->getHtmlBody(); ?>
        </div>

        <?
    endif;
    ?>
</div>
</body>
</html>