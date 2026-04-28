<?php
	require 'templator.php';
?>
<?= $head ?>
<style>
	label {
		display: block;
		line-height: 32px;
		float: left;
	}
	form input {
		float: right;
		display: block;
		line-height: 32px;
	}
	form div {
		margin-bottom: 1em;
	}
</style>
<?= $main ?>
<?php if($_SERVER['REQUEST_METHOD'] !== 'POST') { ?>
<h1 style="margin-top: 0px;">Register to the site</h1>
<form style="margin: 36px 64px; width: 23em; height: 35em; border: 1px solid grey; padding: 1em; position: relative;" method="POST">
	<div class="cf">
		<label for="u">Username:</label>
		<input id="u" name="u" type="text"/>
	</div>
	<div class="cf">
		<label for="p">Password:</label>
		<input id="p" name="p" type="password"/>
	</div>
	<div class="cf">
		<label for="p">Secrecy Key:</label>
		<input id="p" name="p" type="text"/>
	</div>
	<button type="submit" class="h3d-button" style="font-size: 2em; position: absolute; bottom: 1em; left: 50%; transform: translateX(-50%)">
		Register!
	</button>
</form>
<?php
	} else {
		$db = new SQLite3('videos.db');
		$stmt = $db->prepare('SELECT * FROM users WHERE username = :name');
		$stmt->bindValue(':id', 1, SQLITE3_TEXT);
		$result = $stmt->execute();
		if($result->fetchArray(SQLITE3_ASSOC)) {
			echo '<span style="color: red;"><marquee>User already exists!!</marquee></span>'
		}
	}
?>
<?= $footer1 ?>
<?= $footer2 ?>
<?= $footer3 ?>
