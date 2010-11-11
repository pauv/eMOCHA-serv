<?php defined('SYSPATH') or die('No direct script access.');

return array ( 
	
	'email' => Array
	( 
		'not_empty' => 'Enter your email',
		'validate::email' => 'The email is not valid',
		'unknown' => 'This email is not in our database',
		'send_failure' => 'The verification email could not be sent',
		'default'=> 'The email is not valid'
	),
	
	'code' => Array
	( 
		'not_empty' => 'Enter your code',
		'invalid' => 'Invalid code',
	),

);

