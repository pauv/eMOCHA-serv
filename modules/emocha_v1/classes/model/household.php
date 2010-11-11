<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Household extends ORM_Encrypted {
	
	protected $_primary_key = 'code';
	protected $_has_many = array (
								'patients'=>array('foreign_key'=>'household_code'),
								'form_datas'=>array('foreign_key'=>'household_code')
							);

	
	// fields to be stored encrypted in DB
	protected $_encrypted = array('village_code', 'gps', 'gps_long', 'gps_lat');
	
	
	
	/*
	Save a household object
	from uploaded form data
	*/
	public static function save_from_form_data($form) {
		$household = ORM::factory('household')
						->where('code', '=', $form->household_code)
						->find();
		if(! $household->loaded()) {
			$household->code = $form->household_code;
		}
		$xml_obj = simplexml_load_string(stripslashes($form->xml_content));
		$household->village_code = $xml_obj->location->village_code;
		$household->gps = $xml_obj->location->gps_coordinates;
		$gps_arr = explode(' ', $household->gps);
		if (count($gps_arr)>=2) {
			$household->gps_lat = $gps_arr[0];
			$household->gps_long = $gps_arr[1];
		}
		
		
		$household->register_phone_id = $form->creator_phone_id;
		/*
		TODO: add extra fields when they are done
		*/
		
		
		$household->save();
		return $household;
	}
	
	
	
	
	
	/*
	Search for households with filters
	@param array $_POST
	@return array households
	*/
	public static function search ($post = array()) {
	
		$sql = "SELECT code FROM households WHERE ";
		
		// filter by number of patients in household
		if(($num_patients_operator = Arr::get($post, 'num_patients_operator')) && ($num_patients = Arr::get($post, 'num_patients'))) {
			$sql .= "code IN (
						SELECT household_code
						FROM patients
						GROUP BY household_code
						HAVING COUNT(*) ".$num_patients_operator." ".$num_patients."
						) ";
		}
		
		
		// run query and return results
		$result = DB::query(Database::SELECT, $sql)->execute();
		$households = array();
		foreach($result->as_array() as $row) {
			$households[] = ORM::factory('household', $row['code']);
		}
		return $households;
		
	}

}