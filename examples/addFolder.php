<?php
use Humps\MailManager\Factories\ImapFactory;

require_once '../vendor/autoload.php';

function getPost($name, $returnIfNotSet = null){
	return (isset($_POST[$name])) ? $_POST[$name] : $returnIfNotSet;
}

$level = getPost('level');
$name = getPost('name','');
$folder = getPost('folder');

if($name == '') {
	$message = "Please Enter a Folder Name";
	header('Location:index.php?message='.$message);
	die();
} else {
	// Create an imap object. It doesn't matter what folder we open, so we stick with the default INBOX.
	$imap = ImapFactory::create();
	$mailboxService = new \Humps\MailManager\ImapMailboxService($imap);
	$mailboxService->createFolder($name, $level);

	$message="Folder Added Successfully!";
	header("Location:index.php?folder=$folder&success=$message");
}

