<?php
set_time_limit(0);
$time_start = microtime(true);
use Carbon\Carbon;
use Humps\MailManager\Factories\ImapFactory;
use Humps\MailManager\Factories\ImapFolderCollectionFactory;
use Humps\MailManager\Factories\ImapMailManagerFactory;
use Humps\MailManager\Factories\ImapMessageCollectionFactory;
use Humps\MailManager\ImapMailboxService;
use Humps\MailManager\ImapMailManager;

require_once '../vendor/autoload.php';
$folder = (isset($_REQUEST['folder'])) ? $_REQUEST['folder'] : 'INBOX';
$imap = ImapFactory::create($folder);
$mailboxService = new ImapMailboxService($imap);
//$messageNumbers = $mailboxService->getMessagesBetween('2016-01-17','2016-01-18');

$folders = ImapFolderCollectionFactory::create($mailboxService->getAllFolders());
// Uses getMailbox() method from the ImapConnection helper trait, or you can do: $imap->getConnection()->getMailbox()->getFolder();
$currentFolder = $mailboxService->getMailbox()->getFolder();
$aliases = $mailboxService->getAliases();
$isTrash = false;
if(isset($aliases['trash'])) {
	if($aliases['trash'] == $currentFolder) {
		$isTrash = true;
	}
}

// Show all messages for the trash folder, as these are usually auto deleted at specified time periods. So there shouldn't be too many!
if($isTrash) {
	$messageNumbers = $mailboxService->getAllMessages();
} else {
	$messageNumbers = $mailboxService->getMessagesAfter(time());
}

$messages = ImapMessageCollectionFactory::create($messageNumbers, $imap);
?>

<html>
<head>
	<title>Emails</title>
	<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
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
	<? if(isset($_REQUEST['message'])): ?>
		<div class="alert alert-danger">
			<?= $_REQUEST['message'] ?>
		</div>
	<? endif ?>
	<? if(isset($_REQUEST['success'])): ?>
		<div class="alert alert-success">
			<?= $_REQUEST['success'] ?>
		</div>
	<? endif ?>
	<h2><?= $currentFolder ?></h2>
	<div class="panel panel-default col-md-2 pull-left" style="margin-bottom:10px;clear:both;padding:10px;">
		<span class="glyphicon glyphicon-plus folder-glyph"></span><a href="#" id="addFolder">Create New Folder</a>
		<div id="folderForm" style="display:none;">
			<form method="post" action="addFolder.php" style="margin-top:10px;">
				<div class="form-group">
					<select class="form-control" name="level">
						<option value="">Top Level Folder</option>
						<option value="<?= $currentFolder ?>">Subfolder of <?= $currentFolder ?></option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Folder Name" name="name"/>
				</div>
				<input type="hidden" name="folder" value="<?= $currentFolder ?>">
				<input type="submit" value=" Add " class="btn btn-primary form-control"/>
			</form>
		</div>
	</div>
	<div class="panel panel-default pull-left col-md-2 mailboxes" style="clear:both;">
		<ul class=" folders">
			<? if(count($folders)): ?>
				<? foreach($folders as $folder): ?>
					<li><span class="glyphicon glyphicon-inbox folder-glyph"></span><a
							href="index.php?folder=<?= $folder->getName() ?>"><?= $folder->getName() ?></a>
						<? if(isset($aliases['trash'])) {
							if($aliases['trash'] == $folder->getName()) {
								echo "({$mailboxService->getMessageCount($folder->getName())})";
							}
						} ?>
					</li>
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
			if(count($messages)):
				foreach($messages as $i => $message):
					?>
					<tr>
						<td><?= ($message->getFrom()->get(0)->getPersonal()) ? $message->getFrom()->get(0)->getPersonal() : htmlspecialchars($message->getFrom()->get(0)->getEmailAddress()) ?>
						<br /><?=$message->getUid()?>
						</td>
						<td class="<?= (! $message->isRead()) ? 'unread' : '' ?>">
							<a href="showMessage.php?mid=<?= $message->getMessageNum() ?>&folder=<?= $currentFolder ?>"><?= $message->getSubject() ?> </a>
						</td>
						<td><span
								class="glyphicon glyphicon-paperclip" <?= (! $message->hasAttachments()) ? 'style="visibility:hidden;"' : '' ?>></span>
							<?= $message->getDate()->format('d-m-Y \\@ h:m:s A'); ?></td>
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

		<? if($isTrash): ?>
			<div>
				<a href="emptyTrash.php?folder=<?= $currentFolder ?>">
					<div class="btn btn-danger">Empty Trash</div>
				</a>
			</div>
		<? endif ?>

	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		var visible = false;
		$('#addFolder').click(function () {
			if (visible) {
				$('#folderForm').slideUp();
				visible = false;
			} else {
				$('#folderForm').slideDown();
				visible = true;
			}
		});

	});
</script>
</body>
</html>
<?
// Script end
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
//execution time of the script
echo '<b>Total Execution Time:</b> ' . $execution_time . ' Seconds';
?>
