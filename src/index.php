<?php
	require __DIR__ . '/helpers/list.php';
	$app = require __DIR__ . '/init.php';
?>
<?= $app['head'] ?>
<?= $app['main'] ?>
<?= videoList('', $app['db']) ?>
<?= $app['footer1'] ?>
sude bar
<?= $app['footer2'] ?>
<?= $app['footer3'] ?>
