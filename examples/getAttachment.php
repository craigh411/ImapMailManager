<?php
use Humps\MailManager\Factories\ImapFactory;
use Humps\MailManager\Factories\ImapMessageFactory;
use Humps\MailManager\ImapMessageService;

set_time_limit(0);


require_once '../vendor/autoload.php';

$mid = $_REQUEST['mid'];
$folder = $_REQUEST['folder'];
$filename = $_REQUEST['filename'];

$imap = ImapFactory::create($folder);
$message = ImapMessageFactory::create($mid, $imap);
$messageService = new ImapMessageService($message, $imap);


if ($path = $messageService->downloadAttachmentByFilename($filename)) {
    // download to browser
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($path);
    
    $dir = dirname($path);
    unlink($path);
    rmdir($dir);
    rmdir($folder);
}


