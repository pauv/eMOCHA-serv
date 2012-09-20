<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->title = 'eMocha - Admin';
		$this->template->nav = View::factory('main/nav');
		$this->template->curr_menu = 'main';

	}
	
	
	public function action_index()
	{
		Request::instance()->redirect('main/map');
	}
	
	
	
	/*
	 * Patients routes
	 * */
	public function action_map() {
		
		$locations = array();
		$patient_code = '';
		$date = '';
		
		
		if($_POST) {
			$post = Arr::xss($_POST);
			$patient = ORM::factory('patient',Arr::get($post, 'patient_code'));
			$patient_code = $patient->code;
			$date = $post['date'];
			if($post['date']) {
				$locations = ORM::factory('phone_location')
					->where('phone_id','=',$patient->phone_id)
					->and_where(DB::expr('DATE(ts)'),'=',$post['date'])
					->and_where('ts','>=',$patient->activation_ts);
				if($patient->active==0) {
					$locations = $locations->and_where('ts','<=',$patient->deactivation_ts);
				}
				$locations = $locations->order_by('ts', 'ASC')
					->find_all();
			}
			else {
				$locations = ORM::factory('phone_location')
					->where('phone_id','=',$patient->phone_id)
					->and_where('ts','>=',$patient->activation_ts);
					if($patient->active==0) {
						$locations = $locations->and_where('ts','<=',$patient->deactivation_ts);
					}
					$locations = $locations->order_by('ts', 'ASC')
					->find_all();
			}
		}
		$content = $this->template->content = View::factory('routes/map');
		$content->locations = $locations;
		$content->patients = Model_Patient::get_code_val_array();
		$content->dates = Model_Phone_Location::get_dates_array();
		$content->patient_code = $patient_code;
		$content->date = $date;
	}
	
	// ajax form update
	public function action_map_form($patient_code) {
		
		$content = $this->template->content = View::factory('routes/form');
		$this->auto_render=FALSE;
		$date = '';
		
		$patient = ORM::factory('patient',$patient_code);
		$phone_id = $patient->phone_id;

		$content->patients = Model_Patient::get_code_val_array();
		$content->dates = Model_Phone_Location::get_dates_array($phone_id);
		$content->patient_code = $patient_code;
		$content->date = $date;
		
		echo $content->render();
	}
	
	
	
}
