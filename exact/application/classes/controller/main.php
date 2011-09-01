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
	 * Demo of presenting households on a map
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
					->order_by('ts', 'ASC')
					->find_all();
			}
			else {
				$locations = ORM::factory('phone_location')
					->where('phone_id','=',$patient->phone_id)
					->order_by('ts', 'ASC')
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
	
}
