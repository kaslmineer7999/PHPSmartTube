<?php
	require 'templator.php';
	require 'env.php';
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
			// stateless session_id
			// make sure you use HTTPS or the cookies will not function as i use Secure in them
			header('Set-Cookie: session_id=' . hash_hmac('sha256', $_POST['u'] . $row['key'] . floor(time() / 14400), $TOPSECRET) . '; HttpOnly; Max-Age=86400; Secure; SameSite=Lax', false);
			header('Set-Cookie: username=' . $_POST['u'] . '; HttpOnly; Max-Age=86400; Secure; SameSite=Lax', false);
			header('Location: /');
			//die();
		} else {
			echo '<font color="red"><b>Incorrect Password</b></font>';
		}
	}
?>
<?= $footer1 ?>
<?= $footer2 ?>
<?= $footer3 ?>
