<?php

// This is a example file

return [
	// True or false:
	//	If true, then will use the CloudinaryCDN for video and thumbnail files
	//	If false, then will use local disk
	'ONLINEMODE' => false,
	// String:
	// 	Mandotory if $ONLINEMODE is true. This is the key for the CloudinaryCDN
	//	Keep this a secret.
	//	If $ONLINEMODE is false, then set this to a empty string ('')
	'CLOUDINARYKEY' => '',
	// String:
	// 	This is used in session_id's. Keep this a constant (so no time() or smilar)
	//	Make sure this is long and not easily guessable, replace the example input
	//	Keep this a secret
	'TOPSECRET' => 'EXAMPLE INPUT',
	// String:
	// 	Directory used for video files. Keep this a constant
	// 	Only required if using offline mode (ONLINEMODE is false), otherwise
	// 	not used
	'VIDEODIR' => $_SERVER['DOCUMENT_ROOT'] . '/videos/',
	// String:
	//	Directory used for thumbnail image files. Keep this a constant
	// 	Only required if using offline mode (ONLINEMODE is false), otherwise not used
	'THUMBDIR' => $_SERVER['DOCUMENT_ROOT'] . '/thumbs/',
	// String:
	// 	Video encoder, I recomend making this a H264/AVC encoder. Keep this a constant
	// 	Only used in offline mode, otherwise not used
	'VIDEOENCODER' => 'libx264',
	'VIDEOPROFILE' => 'high',
	'VIDEOPRESET'  => 'ultrafast',
	'VIDEOCRF'     => '23',
	// String:
	// 	Audio encoder stuff, I recomend not touching it
	'AUDIOENODER'  => 'aac',
	'AUDIOBITRATE' => '192k'
];
