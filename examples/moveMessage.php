<?php
use Humps\MailManager\Factories\ImapFactory;
use Humps\MailManager\ImapMailboxService;

require_once '../vendor/autoload.php';
$mid = (isset($_REQUEST['mid'])) ? $_REQUEST['mid'] : null;
$folder = (isset($_REQUEST['folder'])) ? $_REQUEST['folder'] : null;
$to = (isset($_REQUEST['to'])) ? $_REQUEST['to'] : null;
if($mid && $folder) {
	$imap = ImapFactory::create($folder);
	$mailboxService = new ImapMailboxService($imap);
	if(! $mailboxService->moveMessages($mid, $to)) {
		die(imap_last_error());
	}
	$message = "Message Successfully Moved To " . $to;
	header('Location:index.php?folder=' . $to . '&success=' . $message);
} elseif(! $mid) {
	die('Invalid Message Number');
} elseif(! $folder) {
	die('Invalid From Folder');
} else {
	die('Invalid To Folder');
}