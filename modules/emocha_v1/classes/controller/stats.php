<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Stats extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->title = 'Stats';
		$this->template->nav = View::factory('stats/nav');
		$this->template->curr_menu = 'stats';

	}
	
	public function action_index() {
		Request::instance()->redirect('stats/data');
	}
	
	
	/*
	 * export form and results as html table
	 */
	public function action_export($form_id=false) {

		$content = $this->template->content = View::factory('stats/export');
		
		// get all form types
		$content->forms = ORM::factory('form')->find_all();
		$content->selected_form_id='';
		
		// individual form type selected
		if($form_id) {
		
			$form = ORM::factory('form', $form_id);
			$form_exporter = new Form_Exporter($form);
			$content->table = $form_exporter->get_as_html_table();
			$content->selected_form_id = $form->id;
		}
				
	}
	
	
	/*
	 * export form and results as tab separated file for spreadsheets
	 * enforces immediate download
	 */
	public function action_export_as_csv($form_id=false) {
		
		// individual form type selected
		if($form_id) {

			$form = ORM::factory('form', $form_id);
			$form_exporter = new Form_Exporter($form);
			$data = $form_exporter->get_as_csv();
			$name = $form->get_short_name().".txt";
			
			$this->request->response = $data;
			$this->request->send_file(TRUE, $name);
		}
		
	}
	
	
	
	
	
	
	// ajax call
	public function action_single_form($id) {
	
		 $this->auto_render=FALSE;
		$form_data = ORM::factory('form_data', $id);
		$html = $form_data->display_result();
		$data =  array(
			'status' => 'OK',
			'msg' => 'testing',
			'html' => $html
		);
		$this->request->response = View::factory('stats/form_display', array('form_data'=>$form_data, 'data'=>$data));
	}
	
	
	public function action_referrals() {

		$this->template->title = 'Stats - Enter data';
		$content = $this->template->content = View::factory('stats/referrals');
		$content->referral_visit_logged =  FALSE;
		$content->form_vals = array();
		
		if($referral_id = Arr::get($_POST, 'referral_id')) {
			$form_data = Model_Form_Data::get_by_referral_id($referral_id);
			if(! $form_data->loaded()) {
				$content->errors = array(Kohana::message('formdata', 'referral_id_not_found'));
			}
			else {
				$patient = ORM::factory('patient')->where('code', '=', $form_data->patient_code)->find();
				if(! $patient->log_referral_visit($referral_id, $form_data->id)) {
					$content->errors = array(Kohana::message('formdata', 'referral_not_logged'));
				}
				else {
					$content->referral_visit_logged = TRUE;
					$content->patient = $patient;
					$content->form = $form_data->form;
				}
			}
		}
		
				
		

	}
	
	public function action_excel($fname) {
		$this->load->model('uploaded_data');
		$this->uploaded_data->export_to_excel($fname);
	}
	
	
	/*
	 * Demo of presenting households on a map
	 * */
	public function action_data($order_by = 'village') {
	
		$household_code = Arr::get($_POST, 'household_code', '');
	
		$content = $this->template->content = View::factory('stats/data');
		if($order_by=='household') {
			if($household_code) {
				$content->households = ORM::factory('household')->where('code', 'LIKE', '%'.$household_code.'%')->order_by('code', 'ASC')->find_all();
			}
			else {
				$content->households = ORM::factory('household')->order_by('code', 'ASC')->find_all();
			}
			
		}
		else {
			$content->households = ORM::factory('household')->order_by('village_code', 'ASC')->find_all();
		}
		$content->order_by = $order_by;
		$content->household_code = $household_code;
	
	}
	
	/*
	 * Demo of presenting households on a map
	 * */
	public function action_map() {
	
		$households = ORM::factory('household')->find_all();
		
		$long_sum = 0;
		$lat_sum = 0;
		$total = 0;
		foreach($households as $household) {
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
		$content = $this->template->content = View::factory('stats/map');
		$content->gmaps_js = View::factory('googlemaps/households', array(
									'google_maps_key' => Kohana::config('googlemaps.key'),
									'lat_center' => $lat_center,
									'long_center' => $long_center,
									'households' => $households,
									));
	
	}
}
