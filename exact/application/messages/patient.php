<?php defined('SYSPATH') or die('No direct script access.');

return array ( 
	
	'email'=> array(
		'email'=>'Invalid email address',
	),
	'phone_id'=> array( 
		'default'=>'No phone selected',
		'imei_available'=> 'Phone already in use by an active patient'
	),
	'code'=> array( 
		'default'=>'Invalid patient code',
		'code_unique'=> 'Patient code already taken'
	)
	
	
);
