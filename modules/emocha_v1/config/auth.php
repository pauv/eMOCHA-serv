<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'driver' => 'ORM',
	'hash_method' => 'sha1',
	'salt_pattern' => '2, 3, 5, 8, 13, 15, 19, 21, 27, 30',
	'lifetime' => 1209600,
	'session_key' => 'auth_user',
	'users' => array
	(
		// 'admin' => 'b3154acf3a344170077d11bdb5fff31532f679a1919e716a02',
	),
);
