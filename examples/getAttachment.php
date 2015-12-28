<?php
set_time_limit(0);

use Humps\MailManager\ImapMailManager;


require_once '../vendor/autoload.php';

$mid = $_REQUEST['mid'];
$folder = $_REQUEST['folder'];
$filename = $_REQUEST['filename'];

$mailManager = new ImapMailManager($folder);

if ($path = $mailManager->downloadAttachments($mid, $filename)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($path.'/'.$filename);
}


