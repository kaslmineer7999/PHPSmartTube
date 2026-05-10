<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/helpers/session_id.php';

$db = new SQLite3(__DIR__ . '/../' . 'videos.db');
$env = require __DIR__ . '/../env.php';

return [
	'db' => $db,
	'layout' => [
		'head' => file_get_contents(__DIR__ . '/html/head.html'),
		'main' => (require __DIR__ . '/helpers/main.php')($db, $env['TOPSECRET']),
		'footers' => [
			file_get_contents(__DIR__ . '/html/footer1.html'),
			file_get_contents(__DIR__ . '/html/footer2.html'),
			file_get_contents(__DIR__ . '/html/footer3.html')
		]
	],
	'env' => $env
];

unset($db);
unset($env);
