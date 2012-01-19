<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Handsets extends Emocha_Controller_Handsets {

	public function action_phones() {
	
		$this->template->title = 'Phones';
		$content = $this->template->content = View::factory('handsets/phones');
		
		$content->phones = ORM::factory('phone')->where('language','=',$this->language)->find_all();

	}
	
	public function action_location() {
		
		$this->template->title = 'Locations';	
		$content = $this->template->content = View::factory('handsets/location');
		
		$phones = ORM::factory('phone')->where('language','=',$this->language)->find_all();
		$markerDataJS = $this->_get_gmaps_js($phones);
		$content->gmaps_js = View::factory('googlemaps/gmaps_js', array(
									'markerDataJS' => $markerDataJS,
									'google_maps_key' => Kohana::config('googlemaps.key')
									));		
	}
}