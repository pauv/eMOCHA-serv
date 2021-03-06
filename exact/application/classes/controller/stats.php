<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Stats extends Emocha_Controller_Stats {
/*
	public function action_index() {
		Request::instance()->redirect('stats/data');
	}
*/	
	/*
	 * Get list of all patients for Stats page
	 */
	 public function action_data()
	{
	
		$content = $this->template->content = View::factory('stats/data');
		
		
		/*
		TODO: remove paging
 		$request = Arr::xss($_REQUEST);
		$pagination = Pagination::factory(array(
			'total_items'    => Model_Patient::get_count(),
			'items_per_page' => 10,
		));

		$content->patients = Model_Patient::get_list($pagination->items_per_page, $pagination->offset, $ord);
		$content->current_url = URL::site($this->request->uri).'?'.http_build_query($_GET, '&');
		$content->pagination = $pagination->render();
		*/
		$content->patients = ORM::factory('patient')->find_all();
	
	}
	
	
	 public function action_export_gps()
	{
 		$name = "gps.txt";
		$data = Model_Phone_Location::export_csv();
		$this->request->response = $data;
		$this->request->send_file(TRUE, $name);
	
	}
	
	
	
}
