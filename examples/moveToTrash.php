<?php
use Humps\MailManager\Factories\ImapFactory;
use Humps\MailManager\ImapMailboxService;

require_once '../vendor/autoload.php';
$mid = (isset($_REQUEST['mid'])) ? $_REQUEST['mid'] : null;
$folder = (isset($_REQUEST['folder'])) ? $_REQUEST['folder'] : null;
if($mid && $folder) {
	$imap = ImapFactory::create($folder);
	$mailboxService = new ImapMailboxService($imap);
	$mailboxService->moveToTrash($mid);
	$message = "Message Successfully Moved To Trash";
	header('Location:index.php?folder='.$folder.'&success='.$message);

} elseif(! $mid) {
	die('Invalid Message Number');
} else {
	die('Invalid Folder');
}