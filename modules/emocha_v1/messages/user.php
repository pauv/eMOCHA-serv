<?php defined('SYSPATH') or die('No direct script access.');

return array ( 
	
	'email' => Array
	( 
		'not_empty' => 'Enter your email',
		'validate::email' => 'Your email is not valid',
		'email_available' => 'This email is not available',
		'default'=> 'Your email is not valid'
	),
	
	'username' => Array 
	( 
		'not_empty' => 'Enter your username',
		'regex' => 'Your username is not valid',
		'username_available' => 'This username is not available',
		'min_length' => 'Your username is too short',
		'max_length' => 'Your username is too long',
		'invalid' => 'The username entered does not appear to be valid. Please try entering it again.',
	),
	
	'password' => Array 
	( 
		'not_empty' => 'Enter your password',
		'matches' => 'The password entered does not match the password on our records.',
		'strength' => 'The password entered is too weak. For better security please include at least one special character or number.',
		'min_length' => 'Your password is too short',
		'max_length' => 'Your password is too long',
		'default' => 'Your password is not valid.',
	),
	
	'password_confirm' => Array 
	( 
		'not_empty' => 'Enter your password confirmation.',
		'matches' => 'Your password confirmation does not match.'
	),
	
	'first_name' => Array
	( 
		'not_empty' => 'Enter your first name'
	),
	
	'last_name' => Array
	( 
		'not_empty' => 'Enter your last name'
	),


);
