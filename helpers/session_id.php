<?php
	function create_session_id($u, $rowkey, $tops) {
		return hash_hmac('sha256', $u . $rowkey . floor(time() / 14400), $tops);
	}
?>
