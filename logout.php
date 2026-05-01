<?php
	header('Set-Cookie: username=; HttpOnly; Max-Age=0; Secure; SameSite=Lax', false);
	header('Set-Cookie: session_id=; HttpOnly; Max-Age=0; Secure; SameSite=Lax', false);
	header('Location: ' . urldecode($_GET['return']), false);
?>

