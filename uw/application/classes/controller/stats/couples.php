<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Stats_Couples extends Controller_Site {


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
	public function action_export() {

		$content = $this->template->content = View::factory('stats/export');
		
		// get all form types
		$content->forms = ORM::factory('form')->find_all();
		
		$content->table = Household_Couples::get_as_table();
		$content->selected_form_id = 'couples';
				
	}
	
	
	/*
	 * export form and results as tab separated file for spreadsheets
	 * enforces immediate download
	 */
	public function action_export_as_csv() {
		
	
		$data = Household_Couples::get_as_table('csv');
		$name = "tested_couples.csv";
			
		$this->request->response = $data;
		$this->request->send_file(TRUE, $name);
	
		
	}

	
	


}