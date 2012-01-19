<?php defined('SYSPATH') or die('No direct script access.');

class Emocha_Controller_Handsets extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->nav = View::factory('handsets/nav');
		$this->template->curr_menu = 'handsets';

	}
	
	public function action_index() {
		Request::instance()->redirect('handsets/phones');
	}
	
	public function action_phones() {
	
		$this->template->title = 'Phones';
		$content = $this->template->content = View::factory('handsets/phones');
		$content->phones = Phone::get_phone_list();

	}
	
	public function action_location() {
		
		$this->template->title = 'Locations';	
		$content = $this->template->content = View::factory('handsets/location');
		
		$phones = Phone::get_gps_phone_list();
		$markerDataJS = $this->_get_gmaps_js($phones);
		$content->gmaps_js = View::factory('googlemaps/gmaps_js', array(
									'markerDataJS' => $markerDataJS,
									'google_maps_key' => Kohana::config('googlemaps.key')
									));		
	}
	
	private function _get_gmaps_js($phones) {
		
		$markerDataJS = '';
		foreach ($phones as $phone) {
			
			if ( $phone->last_connect_ts > time() - 7200) {
				$icon = 'recentIcon';
			} else {
				$icon = 'oldIcon';
			}
	
			$loc = explode(' ', $phone->gps);
			
			$js = sprintf(
				'tMarker=new GMarker(new GLatLng(%s,%s), { icon:%s }); '.
				'tMarker.PID=%d; '.
				'tMarkers.push(tMarker);'."\n", 
				$loc[0], $loc[1], $icon, $phone->id
			);
	
			$js .= sprintf(
				'pPatientData[%d]="<b>%s</b><br/>%s";'."\n",
				$phone->id,
				$phone->comments,
				date('d-m-Y H:i', (3600*0)+$phone->last_connect_ts)
			);	
			$markerDataJS .= $js;
			
		}
		return $markerDataJS;
	}
	
	public function action_add() {
		$this->template->title = 'Add handset';
		$this->template->content = View::factory('handsets/add');
	}
}
