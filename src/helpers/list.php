<?php
	function videoList($r, $db){
		$results = $db->query("SELECT * FROM videos");

		ob_start();
		while($row = $results->fetchArray(SQLITE3_ASSOC)) {?>
			<div class="item">
				<div class="pic">
					<a href="video.php?id=<?= $row['id'] ?>">
						<img src="<?= htmlentities($row['thumbURL']) ?>"/>
					</a>
				</div>
				<div class="h-desc"><?= htmlentities($row['title']) ?></div>
				<div class="filename"><?= htmlentities(mb_strimwidth($row['desc'], 0, 25, "...")); ?></div>
				<div class="info">By <span class="username"><?= htmlentities($row['username']) ?></span></div>
				<?= $r ?>
			</div>
<?php
		};
		$db->close();
		return ob_get_clean();
	};
?>
