<?php
	require 'templator.php';
	require 'env.php';
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
		<input id="p" name="p" type="text" disabled="disabled"/>
	</div>
	<button type="submit" class="h3d-button" style="font-size: 2em; position: absolute; bottom: 1em; left: 50%; transform: translateX(-50%)">
		Register!
	</button>
</form>
<?php
	} else {
		$db = new SQLite3('videos.db');
		$stmt = $db->prepare('SELECT * FROM users WHERE username = :name');
		$stmt->bindValue(':name', $_POST['u'], SQLITE3_TEXT);
		$result = $stmt->execute();
		if($result->fetchArray(SQLITE3_ASSOC)) {
			header('Refresh: 5, url=' . urldecode($_GET['return']));
			echo '<span style="color: red;"><marquee>User already exists!!</marquee></span>';
			echo '<a href="' . htmlspecialchars(urldecode($_GET['return'])) . '">Return to original page</a>';
		} else {
			$my_time = time();
			$stmt = $db->prepare('INSERT INTO users (timestamp, username, key) VALUES (:time, :name, :key)');
			$stmt->bindValue(':time', $my_time, SQLITE3_INTEGER);
			$stmt->bindValue(':name', $_POST['u'], SQLITE3_TEXT);

			$stmt->bindValue('key', password_hash($_POST['p'], PASSWORD_BCRYPT, ["cost" => 12]), SQLITE3_TEXT);
			$results = $stmt->execute();
			if($results) {?>
				You've just finished making an account!<br>
				Now login at <a href="/login.php?<?php htmlspecialchars(urldecode($_GET['return'])) ?>">the login page</a>
<?php
			} else {
				header('Refresh: 5, url=' . urldecode($_GET['return']));
				echo '<span style="color: red;">Can\'t make user account</span>';
				echo '<a href="' . htmlspecialchars(urldecode($_GET['return'])) . '">Return to original page</a>';
			}
		}
	}
?>
<?= $footer1 ?>
<?= $footer2 ?>
<?= $footer3 ?>
