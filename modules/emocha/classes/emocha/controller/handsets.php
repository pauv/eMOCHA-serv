<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Handsets Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Emocha_Controller_Handsets extends Controller_Site {


	/**
	 *  before()
	 *
	 * Run before any action
	 */
	public function before()
	{
		parent::before();
		
		$this->template->nav = View::factory('handsets/nav');
		$this->template->curr_menu = 'handsets';

	}
	
	
	/**
	 *  index()
	 *
	 * Default action
	 */
	public function action_index() {
		Request::instance()->redirect('handsets/phones');
	}
	
	
	/**
	 *  action_phones()
	 *
	 * List all phones
	 */
	public function action_phones() {
	
		$this->template->title = 'Phones';
		$content = $this->template->content = View::factory('handsets/phones');
		$content->phones = Phone::get_phone_list();

	}
	
	
	/**
	 *  action_location()
	 *
	 * Display phone locations based on last connect gps
	 */
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
	
	
	/**
	 *  _get_gmaps_js()
	 *
	 * Format googlemaps javascript
	 *
	 * @param array
	 */
	protected function _get_gmaps_js($phones) {
		
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
	
	
	/**
	 *  action_add()
	 *
	 * Add a phone
	 */
	public function action_add() {
		$this->template->title = 'Add handset';
		$this->template->content = View::factory('handsets/add');
	}
}
