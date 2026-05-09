<?php
	$app = require __DIR__ . '/init.php';
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$stmt = $app['db']->prepare('SELECT * FROM users WHERE username = :name');
		$stmt->bindValue(':name', $_COOKIE['username'], SQLITE3_TEXT);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);

		// Assume expired session_id, faker, or unregister until otherwise proven
		$username = 'Guest';

		if(hash_equals(create_session_id($_COOKIE['username'], $row['key'], $app['env']['TOPSECRET']), $_COOKIE['session_id'])) {
			$username = $_COOKIE['username'];
		}

		//--- end user verification

		$stmt = $app['db']->prepare(<<<SQL
			INSERT INTO guestbook (timestamp, upvote, downvote, bodytext, username)
			VALUES (:time, 0, 0, :textdata, :username)
		SQL);
		$stmt->bindValue(':time', time(), SQLITE3_INTEGER);
		$stmt->bindValue(':textdata', $_POST['textdata'], SQLITE3_TEXT);
		$stmt->bindValue(':username', $username, SQLITE3_TEXT);
		if($stmt->execute()) {
			// we're done, we can go home
			header('Location: /guestbook.php');
			ob_flush();
			flush();
			die();
		} else {
			// set a cookie that the get request will auto-nuke
			header('Set-cookie: guestbookfailure=true; HttpOnly');
			ob_flush();
			flush();
			die();
		}
	}

	$failmessage = '';

	if(($_COOKIE['guestbookfailure'] ?? '') === 'true') {
		$failmessage = '<font color="red"><b>Some error happened. Couldn\'t post entry</b></font><br>';
		header('Set-cookie: guestbookfailure=; HttpOnly; Max-Age=0');
	}

	$bbparse = new \Nbbc\BBCode();
	$bbparse->AddRule();

?>
<?= $app['layout']['head'] ?>
<link href="/css/GuestBookExtras.css" rel="stylesheet"/>
<?= $app['layout']['main'] ?>
<?= $failmessage ?>
<h1 style="margin-top: 0px"><big>G</big>uest<big>B</big>ook</h1>
<p class="gbpdesc">This is the GuestBook, do whatever you want. I don't care</p>
<form method="POST">
	<fieldset style="text-align: center; margin-bottom: 20px">
		<legend><big>E</big>ntry <big>B</big>ody <big>T</big>ext</legend>
		<textarea name="textdata" style="width: 100%; height: 60ch; background: #444; color: #fff;"></textarea>
	</fieldset>
	<fieldset style="text-align: center; margin-bottom: 20px">
		<legend><big>A</big>ctions</legend>
		<button class="h3d-button" style="font-size: 1.3em;">Submit</button>
	</fieldset>
</form>
<div class="gbentries">
	<?php
		// Schema: CREATE TABLE guestbook (
		// 	id INTEGER PRIMARY KEY,
		// 	timestamp INTEGER NOT NULL,
		// 	upvote INTEGER NOT NULL,
		// 	downvote INTEGER NOT NULL,
		// 	bodytext TEXT NOT NULL,
		//	username TEXT NOT NULL
		// );
		$results = $app['db']->query('SELECT * FROM guestbook ORDER BY timestamp DESC');

		while($row = $results->fetchArray(SQLITE3_ASSOC)) {?>
			<div class="gbentry cf" id="gbid<?= $row['id'] ?>">
				<div class="gbinfo">
					<div class="gbavatar"></div>
					<div class="gbuser"><?= htmlentities($row['username']) ?></div>
					<div class="gbdate"><span><?= $row['timestamp'] ?></span></div>
					<div class="cf gbvotes">
						<div style="float: left; width: 33.33%"><button>^</button></div>
						<div style="float: left; width: 33.33%; line-height: 21.5px;"
							data-upvotes="<?= $row['upvote'] ?>"
							data-downvotes="<?= $row['downvote'] ?>"><?= $row['upvote'] - $row['downvote'] ?></div>
						<div style="float: left; width: 33.33%; transform: rotate(180deg)"><button>^</button></div>
					</div>
				</div>
				<div class="gbcontent"><?= $bbparse->Parse($row['bodytext']) //lib escapes html on its own ?></div>
			</div>
			<?php
		}
	?>
</div>
<?= $app['layout']['footers'][0] ?>
<?= $app['layout']['footers'][1] ?>
<script src="/js/GuestBook.nomain.js"></script>
<?= $app['layout']['footers'][2] ?>
