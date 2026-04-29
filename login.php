<?php
	require 'templator.php';
?>
<?= $head ?>
<?= $main ?>
<?php if($_SERVER['REQUEST_METHOD'] !== 'POST') { ?>
<form style="margin: 36px 64px; width: 23em; height: 35em; border: 1px solid grey; padding: 1em; position: relative;" method="POST">
	<div class="cf">
		<label for="u">Username:</label>
		<input id="u" name="u" type="text"/>
	</div>
	<div class="cf">
		<label for="p">Password:</label>
		<input id="p" name="p" type="password"/>
	</div>
	<button type="submit" class="h3d-button" style="font-size: 2em; position: absolute; bottom: 1em; left: 50%; transform: translateX(-50%)">
		Login
	</button>
</form>
<?php
	} else {
		$db = new SQLite3('videos.db');
		$stmt = $db->prepare('SELECT * FROM users WHERE username = :name');
		$stmt->bindValue(':name', $_POST['u'], SQLITE3_TEXT);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);

		if(password_verify($_POST['p'], $row['key'])) {
			// very unsafe, still thinking of another way
			header('Set-Cookie: key=' . $_POST['p'] . '; HttpOnly; Max-Age=86400');
			header('Set-Cookie: username=' . $_POST['u'] . '; HttpOnly; Max-Age=86400');
			header('Location: /');
			die();
		} else {
			echo '<font color="red"><b>Incorrect Password</b></font>';
		}
	}
?>
<?= $footer1 ?>
<?= $footer2 ?>
<?= $footer3 ?>
