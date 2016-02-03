<?php
use Humps\MailManager\Factories\ImapFactory;
use Humps\MailManager\ImapMailboxService;

require_once '../vendor/autoload.php';

$folder = isset($_REQUEST['folder']) ? $_REQUEST['folder'] : 'INBOX';

$imap = ImapFactory::create();
$mailboxService = new ImapMailboxService($imap);
$mailboxService->emptyTrash();

$message = "Trash Successfully Emptied";
header('Location:index.php?folder='.$folder.'&success='.$message);