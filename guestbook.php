<?php
	@require 'vendor/autoload.php';

	@$bbparse = new JBBCode\Parser();
	$bbparse->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

	require 'templator.php';
?>
<?= $head ?>
<link href="/GuestBookExtras.css" rel="stylesheet"/>
<?= $main ?>
<h1 style="margin-top: 0px"><big>G</big>uest<big>B</big>ook</h1>
<p class="gbpdesc">This is the GuestBook, do whatever you want. I don't care</p>
<div class="gbentries">
	<?php
		$db = new SQLite3('videos.db');
		// Schema: CREATE TABLE guestbook (
		// 	id INTEGER PRIMARY KEY,
		// 	timestamp INTEGER NOT NULL,
		// 	upvote INTEGER NOT NULL,
		// 	downvote INTEGER NOT NULL,
		// 	bodytext TEXT NOT NULL,
		//	username TEXT NOT NULL
		// );
		$results = $db->query('SELECT * FROM guestbook');

		while($row = $results->fetchArray(SQLITE3_ASSOC)) {?>
			<div class="gbentry cf" id="gbid<?= $row['id'] ?>">
				<div class="gbinfo">
					<div class="gbavatar"></div>
					<div class="gbuser"><?= htmlentities($row['username']) ?></div>
					<div class="gbdate"><span><?= $row['timestamp'] ?></span></div>
					<div class="cf gbvotes">
						<div style="float: left; width: 33.33%"><button>^</button></div>
						<div style="float: left; width: 33.33%"><?= $row['upvote'] - $row['downvote'] ?></div>
						<div style="float: left; width: 33.33%; transform: rotate(180deg)"><button>^</button></div>
					</div>
				</div>
				<div class="gbcontent"><?= $bbparse->parse(htmlspecialchars($row['bodytext'], ENT_NOQUOTES))->getAsHtml() ?></div>
			</div>
			<?php
		}
	?>
</div>
<?= $footer1 ?>
<?= $footer2 ?>
<?= $footer3 ?>
