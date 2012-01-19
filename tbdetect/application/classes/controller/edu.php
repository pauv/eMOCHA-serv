<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Edu extends Emocha_Controller_Edu {

	/*
	Customisation of core Edu controller to filter by language session variable.
	*/
	
	// get language variable from session
	public function before()
	{
		parent::before();
	}
	
	
	// list courses files by language
	public function action_courses($action=false) {
		$data['medias'] = ORM::factory('media')->where('type', '=', 'courses')->and_where('language','=',$this->language)->find_all();
		$data['section'] = 'courses';
		$this->template->title = 'Courses';
		$data['action'] = $action;
		$this->template->content = View::factory('edu/file_list', $data);
	}
	
	// list lectures files by language
	public function action_lectures($action=false) {
		$data['medias'] = ORM::factory('media')->where('type', '=', 'lectures')->and_where('language','=',$this->language)->find_all();
		$data['section'] = 'lectures';
		$data['action'] = $action;
		$this->template->title = 'Lectures';
		$this->template->content = View::factory('edu/file_list', $data);
	}
	
	// list library files by language
	public function action_library($action=false) {
		$data['medias'] = ORM::factory('media')->where('type', '=', 'library')->and_where('language','=',$this->language)->find_all();
		$data['section'] = 'library';
		$data['action'] = $action;
		$this->template->title = 'Library';
		$this->template->content = View::factory('edu/file_list', $data);
	}
	
}