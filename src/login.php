<?php
	$app = require __DIR__ . '/init.php';
?>
<?= $app['layout']['head'] ?>
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
<?= $app['layout']['main'] ?>
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
		$stmt = $app['db']->prepare('SELECT * FROM users WHERE username = :name');
		$stmt->bindValue(':name', $_POST['u'], SQLITE3_TEXT);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);

		if(password_verify($_POST['p'], $row['key'])) {
			// stateless session_id
			// make sure you use HTTPS or the cookies will not function as i use Secure in them
			header('Set-Cookie: session_id=' . create_session_id($_POST['u'], $row['key'], $TOPSECRET) . '; HttpOnly; Max-Age=86400; Secure; SameSite=Lax', false);
			header('Set-Cookie: username=' . $_POST['u'] . '; HttpOnly; Max-Age=86400; Secure; SameSite=Lax', false);
			header('Location: ' . urldecode($_GET['return']));
			//die();
		} else {
			header('Refresh: 5, url=' . urldecode($_GET['return']));
			echo '<font color="red"><b>Incorrect Password</b></font><br>';
			echo '<a href="' . htmlspecialchars(urldecode($_GET['return'])) . '">Return to original page</a>';
		}
	}
?>
<?= $app['layout']['footers'][0] ?>
<?= $app['layout']['footers'][1] ?>
<?= $app['layout']['footers'][2] ?>
