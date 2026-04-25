<?php
	require 'templator.php';

	$db = new SQLite3('videos.db');
	$stmt = $db->prepare("SELECT * FROM videos WHERE id = :id");

	$stmt->bindValue(':id', $_GET['id'], SQLITE3_TEXT);

	$result = $stmt->execute();
	$row = $result->fetchArray(SQLITE3_ASSOC);
?>
<?= $head ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
<link rel="stylesheet" href="/SmartTube/VideoPlayerExtras.css"/>
<?= $main ?>
<div class="video">
	<div class="c-video">
		<div class="video-wrapper"><video src="<?= htmlspecialchars($row['videoURL']) ?>">
			Your browser does not support HTML video.
		</video></div>
		<div class="controls">
			<div class="progress-bar" draggable="false">
				<div class="juice" draggable="false"></div>
			</div>
			<button class="pp-btn play v-button"></button>
			<div class="lr-time">
				<button class="v-button vol unmuted"></button>
				&nbsp; &nbsp;
				<span class="curt"></span> /
				<span class="total"></span>
			</div>
		</div>
	</div>
</div>
<h1 style="margin-top: 0px"><?= $row['TITLE'] ?></h1>
<div class="cf">
	<i style="float: left; opacity: 0.6">Views: <span id="views"><?= $row['views'] ?></i>
	<div style="float: right">testing</div>
</div>
<p style="
	border: 0.5em outset #666;
	padding: 1em; margin-left: 2em; margin-right: 2em; margin-top: 2em;
	text-indent: -0.5em;
" class="legacy-terminal"><?= $row['desc'] ?></p>
<?= $footer1 ?>
<?= $footer2 ?>
<script src="/SmartTube/VideoPlayer.nomain.js"></script>
<?= $footer3 ?>
<?php
	$stmt = $db->prepare('UPDATE videos SET views = views + 1 WHERE id = :id');

	$stmt->bindValue(':id', $_GET['id'], SQLITE3_INTEGER);
	$stmt->execute();
?>
