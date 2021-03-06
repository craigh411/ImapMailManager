<?
use Humps\MailManager\Factories\ImapFactory as Imap;
use Humps\MailManager\Factories\ImapFolderCollectionFactory;
use Humps\MailManager\Factories\ImapMessageFactory as ImapMessage;
use Humps\MailManager\ImapMailboxService;
use Humps\MailManager\ImapMessageService;

set_time_limit(0);
require_once '../vendor/autoload.php';
$mid = (isset($_REQUEST['mid'])) ? $_REQUEST['mid'] : null;
$currentFolder = (isset($_REQUEST['folder'])) ? $_REQUEST['folder'] : 'INBOX';
?>

<html>
<head>
	<title>Message</title>
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
			background: #fff;
		}

		.body {
			padding: 10px;
		}
	</style>
</head>
<body>
<div class="message">
	<?
	if(! is_null($mid)):
		$imap = Imap::create($currentFolder);
		$message = ImapMessage::create($mid, $imap);
		$messageService = new ImapMessageService($message, $imap);
		$messageService->downloadEmbeddedImages('images');
		$mailboxService = new ImapMailboxService($imap);
		$folders = ImapFolderCollectionFactory::create($mailboxService->getAllFolders());
		?>
		<table class="table table-striped">
			<tr>
				<td>From:</td>
				<td><?= htmlspecialchars($message->getFrom()->implodeEmails()) ?></td>
			</tr>
			<tr>
				<td>To:</td>
				<td><?= htmlspecialchars($message->getTo()->implodeEmails()) ?></td>
			</tr>
			<tr>
				<td>Subject:</td>
				<td><?= $message->getSubject() ?></td>
			</tr>
			<tr>
				<? if($message->hasAttachments()): ?>
					<td>Attachments:</td>
					<td>
						<?
						foreach($message->getAttachments() as $attachment):
							?>
							<span class="glyphicon glyphicon-paperclip"></span> <?= $attachment->getFilename() ?> <a
							href="getAttachment.php?mid=<?= $mid ?>&filename=<?= urlencode($attachment->getFilename()) ?>&folder=<?= $folder ?>"><span
								class="glyphicon glyphicon-download"></span></a>
						<? endforeach ?>

					</td>
				<? endif ?>
			</tr>
			<tr>
				<td colspan="2">
					<!-- Small button group -->
					<div class="btn-group">
						<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"
								aria-haspopup="true" aria-expanded="false">
							Move Message <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<? if(count($folders)): ?>
								<? foreach($folders as $folder): ?>
									<li>
										<a href="moveMessage.php?mid=<?= $mid ?>&folder=<?= $currentFolder ?>&to=<?= $folder->getName() ?>"><span
												class="glyphicon glyphicon-inbox folder-glyph"></span> <?= $folder->getName() ?>
										</a></li>
								<? endforeach; ?>
							<? endif; ?>
						</ul>
					</div>

					<a href="moveToTrash.php?mid=<?= $message->getMessageNum() ?>&folder=<?= $currentFolder ?>"><span
							class="btn btn-danger" role="button">
						<span class="glyphicon glyphicon-trash"></span> Move To Trash
					</span></a>
				</td>
			</tr>
		</table>

		<div class="body">
			<?= $message->getHtmlBody(); ?>
		</div>
	<? endif ?>


</div>
</body>
</html>