<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/helpers/session_id.php';

$db = new SQLite3(__DIR__ . '/../' . 'videos.db');
$env = require __DIR__ . '/../env.php';
$bb = new \Nbbc\BBCode();
$bb->AddRule('tt', [
	'simple_start' => '<tt>',
	'simple_end' => '</tt>',
	'class' => 'inline',
	'allow_in' => ['listitem', 'block', 'columns', 'inline', 'link']
]);

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
	'env' => $env,
	'bbparser' => $bb
];

unset($db);
unset($env);
