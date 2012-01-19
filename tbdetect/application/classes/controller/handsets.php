<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Handsets extends Emocha_Controller_Handsets {

	public function action_phones() {
	
		$this->template->title = 'Phones';
		$content = $this->template->content = View::factory('handsets/phones');
		
		$content->phones = ORM::factory('phone')->where('language','=',$this->language)->find_all();

	}
}