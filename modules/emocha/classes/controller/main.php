<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->title = 'Main';
		$this->template->nav = View::factory('main/nav');
		$this->template->curr_menu = 'main';

	}
	
	
	public function action_index()
	{
		Request::instance()->redirect('main/map');
	}
	
	
	/*
	 * Demo of presenting data in a map.
	 * The data can be filtered with some HTML inputs.
	 * The data in this example has previously been read
	 * from xml files and introduced in an MySQL database.
	 * This demo is very inefficient.
	 * A much better way to do this would be introducing all
	 * XML data into an eXist database, which allows us to
	 * directly query and filter this data.
	 * 
	public function action_datasel() {
	
		
		// xss clean post vars
 		$post = Arr::xss($_POST);
 		
		$dataList = Model_Form_Data::get_table_data(
			Arr::get($post, 'gender'),
			Arr::get($post, 'tb'),
			Arr::get($post, 'hiv'),
			Arr::get($post, 'age_min'),
			Arr::get($post, 'age_max'),
			Arr::get($post, 'temp_min'),
			Arr::get($post, 'temp_max')
		);
		$markerDataJS = '';
		foreach ($dataList as $row) {
			$markerDataJS .= $this->_get_gmaps_js($row);			
		}
		$content = $this->template->content = View::factory('main/datasel');
		$content->gmaps_js = View::factory('googlemaps/gmaps_js', array(
									'markerDataJS' => $markerDataJS,
									'google_maps_key' => Kohana::config('googlemaps.key')
									));
		
	}
	
	*/
	
	/*
	 * Demo of presenting households on a map
	 * */
	public function action_map($map_type='households', $selected_code=false) {
	
		$this->template->title = 'Data Map';
		
		$post = Arr::xss($_POST);
		
		$selected_item = false;
	
		if ($map_type == 'households') {
			if ($post) {
				$items = Model_Household::search($post);
			}
			else {
				$items = ORM::factory('household')->find_all();
				// a household was selected
				if($selected_code){
					$selected_item = ORM::factory('household', $selected_code);
				}
			}
		}
		elseif ($map_type == 'patients') {
			if ($post) {
				$items = Model_Patient::search($post);
			}
			else {
				$items = ORM::factory('patient')->find_all();
				// a patient was selected
				if($selected_code){
					$selected_item = ORM::factory('patient', $selected_code);
				}
			}
			// make map coordinates unique
			$items = Model_Patient::map_patients($items);
		}
		
		$long_center = 0;
		$lat_center = 0;
		$long_sum = 0;
		$lat_sum = 0;
		$total = 0;
		foreach($items as $item) {
			$household = $map_type=='households' ? $item : $item->household;
			if($household->gps_lat && $household->gps_long) {
				$long_sum+=$household->gps_long;
				$lat_sum+=$household->gps_lat;
				$total++;
			}
		}
		if($total) {
			$long_center = $long_sum/$total;
			$lat_center = $lat_sum/$total;
		}
		$content = $this->template->content = View::factory('main/map', array(
									'map_type' => $map_type,
									'google_maps_key' => Kohana::config('googlemaps.key'),
									'lat_center' => $lat_center,
									'long_center' => $long_center,
									'items' => $items,
									'selected_item' => $selected_item,
									'form_vals' => $post
									));
	
	}
	
	
	
	
	public function action_alarms() {
		$this->template->title = 'Alarms';
		$content = $this->template->content = View::factory('main/alarms');
	}
	
	public function action_messages() {
		$this->template->title = 'Messages';
		$content = $this->template->content = View::factory('main/messages');
	}
	
	
	
	// TODO: there is a similar function in the handsets controller
	// find whatever they have in common and place it in a library or controller.
	private function _get_gmaps_js($row) {
		$xml = $row['xml_obj'];
		
		if ( $row['last_modified'] > time() - 7200) {
			$icon = 'recentIcon';
		} else {
			$icon = 'oldIcon';
		}

		$loc = explode(' ', $xml->location->gps_coordinates);
		
		if (count($loc) != 2) {
			return '';
		}
		
		$js = sprintf(
			'tMarker=new GMarker(new GLatLng(%s,%s), { icon:%s }); '.
		  	'tMarker.PID=%d; '.
		  	'tMarkers.push(tMarker);'."\n", 
			$loc[0], $loc[1], $icon, $row['id']
		);

		// TODO: the next data would be better read on-request, instead
		// of embedding all the patient info in javascript in this page.
		// so, when clicking, an ajax request would be sent to the server,
		// including the patient-data-id or phone-id.
		// the server would return a chunk of html to be shown in the bubble
		if (strlen($xml->patient_image) > 5) {
			$folderName = basename($row['file_path'], '.xml');
			$img_url = sprintf("/sdcard/%s/sdcard/odk/instances/%s/%s", 
				$row['uploader_phone_id'], 
				$folderName, 
				$xml->patient_image
			);
			$img = sprintf("<img onClick='jQuery.slimbox(\\\"%s\\\", \\\"%s\\\");' src='%s' style='float:right;' width='60'>", 
				$img_url, $xml->patient_name, $img_url);				
		} else {
			$img = '';
		}
		
		$js .= sprintf('pPatientData[%d]="%s<b>%s</b><br/>'.
			'%s year old %s<br/>'.
			'Temp: %sºC<br/>'.
			'%s %s<br/>%s";'."\n",
			$row['id'], 
			$img,
			$xml->patient_name, 
			$xml->patient_age,
			$xml->patient_sex == 'm' ? 'male' : 'female',
			$xml->patient_temp,
			$xml->patient_tbc == 'y' ? 'TB ' : '',
			$xml->patient_hiv == 'y' ? 'HIV ' : '',
			date('d-m-Y h:i', 3600*3 + $row['last_modified'])
		);
				
		return $js;
	}	

}
