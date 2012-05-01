<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Stats Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @author     Pau Varela
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @copyright  2012 Pau Varela - pau.varela@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Emocha_Controller_Stats extends Controller_Site {

	/**
	 *  before()
	 *
	 * Run before any action
	 */
	public function before()
	{
		parent::before();
		
		$this->template->title = 'Stats';
		$this->template->curr_menu = 'stats';

		//choose navigation bar according to application type
		$app_type = $this->get_application_type();
		if (isset($app_type)) 
		{
			$this->template->application_type = $app_type; //set for the next controller methods

			if ($app_type == Kohana::config('values.app_type_households')) 
			{

				$this->template->nav = View::factory('stats/nav');
			} elseif ($app_type == Kohana::config('values.app_type_patients_only'))
			{

				$this->template->nav = View::factory('stats/nav_patients_only');
			} elseif ($app_type == Kohana::config('values.app_type_forms_only')){

				$this->template->nav = View::factory('stats/nav_forms_only');
			} else
			{
				$this->template->nav = View::factory('stats/nav_error');
				$this->template->nav->errors = array(Kohana::message('platform', 'application_type_missing'));
			}
		} else
		{
				$this->template->nav = View::factory('stats/nav_error');
				$this->template->nav->errors = array(Kohana::message('platform', 'invalid_application_type'));
		}
	}
	
	private function get_application_type()
	{
    //get application_type
    $app_type = ORM::factory('config')
                ->where('label','=',Kohana::config('values.application_type'))
                ->and_where('type','=',Kohana::config('values.platform'))
                ->find();

    if($app_type->loaded() AND $app_type->content)
			return $app_type->content;
		else 
			return NULL;
	}

	/**
	 *  index()
	 *
	 * chooses where to redirect according to the application_type
	 */
	public function action_index() {
		$app_type = $this->template->application_type;

		if (isset($app_type))
		{

			if ($app_type == Kohana::config('values.app_type_households')) 
			{

				Request::instance()->redirect('stats/datagrid');

			} elseif ($app_type == Kohana::config('values.app_type_patients_only'))
			{

				Request::instance()->redirect('stats/data_patients_only');

			} elseif ($app_type == Kohana::config('values.app_type_forms_only')){

				Request::instance()->redirect('stats/data_forms_only');

			} else
			{
				return; //do something?? (error should have been printed already!)
			}
		} else {
			return; //do something?? (error should have been printed already!)
		}
		//Request::instance()->redirect('stats/datagrid');
	}
	
	public function action_data_patients_only()
	{
		$this->template->title = 'Data by patient';
		$content = $this->template->content = View::factory('stats/data_patients_only');
		
		// xss clean post vars
 		$request = Arr::xss($_REQUEST);
 		$ord = Arr::get($request, 'ord', 'code');

		$content->patients = ORM::factory('patient')->find_all();
	}	

	public function action_data_forms_only()
	{
		$content = $this->template->content = View::factory('stats/data_forms_only');
		$content->patients = ORM::factory('patient')->find_all();
	}
	/**
	 *  action_export()
	 *
	 * Export form and results as html table
	 * 
	 * @param int
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
	

	/**
	 *  action_exportgrid()
	 *
	 * Export form and results as ajax table
	 * 
	 * @param int
	 */
	public function action_exportgrid($form_id=false) {

		$this->template->title = 'Export';
		$content = $this->template->content = View::factory('stats/exportgrid');
		$this->template->curr_nav = 'export';
		// get all form types
		$content->forms = ORM::factory('form')->find_all();
		$content->selected_form_id='';
		
		// individual form type selected
		if($form_id) {
		
			$form = ORM::factory('form', $form_id);
			$form_exporter = new Form_Exporter($form);
			$form_exporter->load_data_as_array();
			$content->columns = $form_exporter->data_as_array['columns'];
			$content->rows = $form_exporter->data_as_array['rows'];
			$content->selected_form_id = $form->id;

		}
				
	}
	
	

	/**
	 *  action_export_as_csv()
	 *
	 * Export form and results as tab separated file for spreadsheets
	 * enforces immediate download
	 * 
	 * @param int
	 */
	public function action_export_as_csv($form_id=false) {
		
		// individual form type selected
		if($form_id) {

			$form = ORM::factory('form', $form_id);
			$form_exporter = new Form_Exporter($form);
			$data = $form_exporter->get_as_csv();
			$name = $form->get_short_name().".xls";
			
			$this->request->response = $data;
			$this->request->send_file(TRUE, $name, array('mime_type'=>'application/vnd.ms-excel'));
		}
		
	}
	
	
	
	/**
	 *  action_single_form()
	 *
	 * Display results for a specific form data
	 * 
	 * @param int
	 */
	public function action_single_form($id) {
	
		$this->auto_render=FALSE;
		$form_data = ORM::factory('form_data', $id);
		$html = $form_data->display_result();
		$data =  array(
			'status' => 'OK',
			'msg' => 'testing',
			'html' => $html
		);
		$app_type = $this->template->application_type;
		if (isset($app_type))
		{
			if ($app_type == Kohana::config('values.app_type_households')) 
			{

				$this->request->response = View::factory('stats/form_display', array('form_data'=>$form_data, 'data'=>$data));

			} elseif ($app_type == Kohana::config('values.app_type_patients_only'))
			{

				$this->request->response = View::factory('stats/patients_only_form_display', array('form_data'=>$form_data, 'data'=>$data));

			} elseif ($app_type == Kohana::config('values.app_type_forms_only')){

				$this->request->response = View::factory('stats/forms_only_form_display', array('form_data'=>$form_data, 'data'=>$data));

			} else
			{
				return; //do something?? (error should have been printed already!)
			}
		}
	}
	
	
	/**
	 *  action_referrals()
	 *
	 * List referrals
	 */
	public function action_referrals() {

		$this->template->title = 'Log referral visit';
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
	
	
	/**
	 *  action_data()
	 *
	 * List form data results
	 */
	public function action_data()
	{
		
		$this->template->title = 'Data by patient';
		$content = $this->template->content = View::factory('stats/data');
		
		// xss clean post vars
 		$request = Arr::xss($_REQUEST);
		$ord = Arr::get($request, 'ord', 'village_code');
		$content->ord = $ord;

		
		$pagination = Pagination::factory(array(
			'total_items'    => Model_Patient::get_count(),
			'items_per_page' => 10,
		));

		$content->patients = Model_Patient::get_list($pagination->items_per_page, $pagination->offset, $ord);
		$content->current_url = URL::site($this->request->uri).'?'.http_build_query($_GET, '&');
		$content->pagination = $pagination->render();
	
	}
	

	
	
	/**
	 *  action_patient()
	 *
	 * Display patient details
	 * 
	 * @param string
	 */
	public function action_patient($code) {
	
		$this->auto_render=FALSE;
		$patient = ORM::factory('patient', $code);
		$this->request->response = View::factory('stats/patient', array('patient'=>$patient));
	}
	
	
	
	/**
	 *  action_housholds()
	 *
	 * List households
	 * 
	 * @param string
	 */
	public function action_households($order_by = 'village') {
	
		$this->template->title = 'Data by household';
		$household_code = Arr::get($_POST, 'household_code', '');
	
		$content = $this->template->content = View::factory('stats/data_households');
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
	 


	/**
	 *  action_datagrid()
	 *
	 * View data in ajax grid
	 */
	 public function action_datagrid()
	{
		$this->template->title = 'Data by patient';
		$content = $this->template->content = View::factory('stats/datagrid');
		$content->patients = ORM::factory('patient')->find_all();
	
	}
	
	

	/**
	 *  action_map()
	 *
	 * Demo of presenting households on a map
	 */
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
	
	
	
	/**
	 *  action_patient_image()
	 *
	 * Display patient image
	 *
	 * @param int
	 */
	public function action_patient_image ($patient_id) {
		$this->auto_render = FALSE;
		$template = View::factory('stats/patient_image');
		$template->patient = ORM::factory('patient')->where('id','=', $patient_id)->find();
		$this->request->response = $template->render();
	}
	

	/**
	 *  action_timegraph()
	 *
	 * Demo charting of data input by date
	 */
	 public function action_timegraph() {
	 	$content = $this->template->content = View::factory('stats/graphs');
	 	$content->points = Stats::get_count_patients_by_date();
	 }
	 
	 
	/**
	 *  action_timegraph()
	 *
	 * Demo symptoms piechart
	 */
	 public function action_piechart() {
	 	$content = $this->template->content = View::factory('stats/pies');
	 	$content->totals = Stats::get_symptom_totals();
	 	$content->title = "Symptoms pie chart";
	 }
}
