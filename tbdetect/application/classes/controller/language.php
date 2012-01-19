<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_Language extends Controller {

	
	// set the language version of the site
	public function action_set($language=false) {
		// get the list of possible languages from the config file
		$languages = Kohana::config('language.languages');
		// check the language is valid and then set it as a session variable
		if($language && is_array($languages) && isset($languages[$language])) {
			Session::instance()->set('language', $language);
			return $language;
		}
		// else return an error message
		return 'error';
	}
	
	
	
}