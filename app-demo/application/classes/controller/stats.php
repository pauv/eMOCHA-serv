<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Stats extends Emocha_Controller_Stats {

	public function action_index() {
		Request::instance()->redirect('stats/datagrid');
	}
	
	/*
	 * Demo charting of data input by date
	 */
	 public function action_symptomgraph() {
	 	$content = $this->template->content = View::factory('stats/symptoms_graph');
	 	$content->points = Stats::get_symptom_count_by_date();
	 	//echo Kohana::debug($content->points);
	 	//exit;
	 }

}