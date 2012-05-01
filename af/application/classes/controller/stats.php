<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Stats extends Emocha_Controller_Stats {
/* let the main controller do the redirect
	public function action_index() {
		Request::instance()->redirect('stats/data');
	}
*/	
/* 20120430 no longer used
	 public function action_data()
	{
		$this->template->title = 'Data by patient';
		$content = $this->template->content = View::factory('stats/data');
		
		// xss clean post vars
 		$request = Arr::xss($_REQUEST);
 		$ord = Arr::get($request, 'ord', 'code');

		$content->patients = ORM::factory('patient')->find_all();
	}
*/	
	
	 public function action_export_gps()
	{
 		$name = "gps.txt";
		$data = Model_Phone_Location::export_csv();
		$this->request->response = $data;
		$this->request->send_file(TRUE, $name);
	
	}
	
	
	
}
