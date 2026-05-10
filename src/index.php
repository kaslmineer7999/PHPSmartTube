<?php
	require __DIR__ . '/helpers/list.php';
	$app = require __DIR__ . '/init.php';
?>
<?= $app['layout']['head'] ?>
<?= $app['layout']['main'] ?>
<?= videoList('', $app['db']) ?>
<?= $app['layout']['footers'][0] ?>
sude bar
<?= $app['layout']['footers'][1] ?>
<?= $app['layout']['footers'][2] ?>
