<?php defined('SYSPATH') OR die('No direct access allowed.');
return array(
	//configuration types:
	'platform' => 'platform',
	'server' => 'server',
	'android' => 'android',

	//platform config values:
	'application_type' => 'application_type',
	'app_type_households' => 'app_type_households',
	'app_type_patients_only' => 'app_type_patients_only',
	'app_type_forms_only' => 'app_type_forms_only',

	'authentication' => 'authentication',
	'usr_only' => 'usr_only',
	'usr_password' => 'usr_password',
	
	'app_time_zone' => 'app_time_zone',

	//server config values:
	'version_name' => 'version_name',
	'admin_alerts_to' => 'admin_alerts_to',
	'available_languages' => 'available_languages',

	//paths
	'form_data_file_path' => 'sdcard/emocha/odk/form_data/',

	//form data file types
	'type_signature' => 'signature'
);
