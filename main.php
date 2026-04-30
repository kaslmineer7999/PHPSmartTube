<?php
	return call_user_func(function() {
		if(!(empty($_COOKIE['username']) && empty($_COOKIE['session_id']))) {
			$replacement = <<<EOV
				<div class="cf" style="margin-bottom: 1em; font-size: 1.25em; text-align: center;">{$_COOKIE['username']}</div>
				<div class="cf" s>
					<div style="float: left; width: 33.33%; text-align: left;"><a href="/login.php" style="color:#00bfff;">Log into another account</a></div>
					<div style="float: left; width: 33.33%; text-align: center;"><a href="/logout.php" style="color:#00bfff;">Logout</a></div>
					<div style="float: left; width: 33.33%; text-align: right;"><a href="/register.php" style="color:#00bfff;">Register a new account</a></div>
				</div>
			EOV;
		} else {
			$replacement = <<<EOV
				<div style="padding-top: 1.5em"></div>
				<div class="cf" style="width: 450px;">
					<div style="float: left; width: 50%; text-align: left;"><a href="/login.php" style="color:#00bfff;">Login</a></div>
					<div style="float: left; width: 50%; text-align: right;"><a href="/register.php" style="color:#00bfff;">Register</a></div>
				</div>
			EOV;
		}
		return str_replace('<!-- ! ACCOUNT ! -->', $replacement, file_get_contents('main.html'));
	});
?>
