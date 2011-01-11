<?php defined('SYSPATH') OR die('No direct access allowed.');

// change the salt pattern
// must be ascending integers up to max 30

return array
(
	'driver' => 'ORM',
	'hash_method' => 'sha1',
	'salt_pattern' => '1, 2, 3, 4, 6, 8, 15, 18, 20, 26',
	'lifetime' => 1209600,
	'session_key' => 'auth_user',
	'users' => array
	(
		// 'admin' => 'b3154acf3a344170077d11bdb5fff31532f679a1919e716a02',
	),
);
