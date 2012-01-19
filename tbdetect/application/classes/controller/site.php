<?php defined('SYSPATH') or die('No direct script access.');

// Global Site template controller
// handles access permissions and loads default display elements

class Controller_Site extends Emocha_Controller_Site 
{

	public function before()
	{
		parent::before();
		
		/*
		Customisation set default language from config,
		if a language has not already been set
		*/
		$language_conf = Kohana::config('language.languages');
		if(! sizeof($language_conf)) {
			echo "please set the languages in the config file";
			exit;
		}
		else {
			$languages = array_keys($language_conf);
			// check if language session not already set
			if(! $language = Session::instance()->get('language')) {
				Session::instance()->set('language', $languages[0]);
				$this->language = $languages[0];
			}
			else {
				$this->language = $language;
			}
		}
	}
}