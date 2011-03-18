<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Patient extends ORM_Encrypted {
	
	protected $_primary_key = 'code';
	protected $_belongs_to = array(
								'household' => array('foreign_key'=>'household_code')
									);
	protected $_has_many = array (
								'form_datas'=>array('foreign_key'=>'patient_code')
							);
							
	//protected $_load_with = array('household');
	
	// fields to be stored encrypted in DB
	protected $_encrypted = array('first_name', 'last_name', 'age', 'sex');

	// vars for mapping
	public $map_lat;
	public $map_long;
	
	
	/*
	Save a patient object
	from uploaded form data
	*/
	public static function save_from_form_data($form) {
		$patient = ORM::factory('patient')
						->where('code', '=', $form->patient_code)
						->find();
		if(! $patient->loaded()) {
			$patient->code = $form->patient_code;
		}
		$xml_obj = simplexml_load_string(stripslashes($form->xml_content));
		$patient->household_code = trim($form->household_code);
		$patient->first_name = trim($xml_obj->individual->first_name);
		$patient->last_name = trim($xml_obj->individual->last_name);
		$patient->age = trim($xml_obj->individual->age);
		if($xml_obj->individual->sex==0) {
			$patient->sex='m';
		}
		elseif($xml_obj->individual->sex==1) {
			$patient->sex='f';
		}
		$patient->register_phone_id = $form->creator_phone_id;
	
		$patient->save();
		return $patient;
	}
	
	
	/*
	Log when a patient visited the clinic
	with a referral slip
	@param string
	@param int
	@return bool
	*/
	public function log_referral_visit($referral_id, $form_data_id) {
		
		// update xml
		$form_data = ORM::factory('form_data', $form_data_id);
		if($form_data->update_xml_node('referral_done', '1')) {
			// store referral
			$referral = ORM::factory('referral');
			$referral->patient_id = $this->id;
			$referral->referral_id = $referral_id;
			$referral->form_data_id = $form_data_id;
			$referral->save();
			return TRUE;
		}
		return FALSE;
		
	}
	
	
	/*
	Get web path to patient's image
	@return string
	*/
	public function get_profile_image() {
		$files_folder = 'sdcard/emocha/patient_files';
		$abs_files_folder = DOCROOT.$files_folder;
		$abs_image_path = $abs_files_folder.'/'.$this->code.'/'.$this->code.'.jpg';
		$image_path = $files_folder.'/'.$this->code.'/'.$this->code.'.jpg';
		// look for this patient's image file
		if(is_file($abs_image_path)) {
			return $image_path;
		}
		return FALSE;
	}
	
	/*
	save patient's image
	@return bool
	*/
	public function save_profile_image($file) {
		$files_folder = 'sdcard/emocha/patient_files';
		$patient_files_folder = DOCROOT.$files_folder.'/'.$this->code;
		if(! is_dir($patient_files_folder)) {
			mkdir($patient_files_folder);
		}
		return upload::save($file, $this->code.'.jpg', $patient_files_folder);
	}
	
	
	/*
	Get core xml data
	*/
	public function get_core_form_data() {
		return ORM::factory('form_data')
						->where('patient_code','=',$this->code)
						->and_where('form_id','=',2)
						->find();
	}
	
	/*
	Get xml node value from core patient data
	param string
	return string
	*/
	public function get_core_form_val ($node) {
		$core_form = $this->get_core_form_data();
		return $core_form->get_xml_node($node);
	}
	
	
	/*
	Search for patients with filters
	@param array $_POST
	@return array patients
	*/
	public static function search ($post = array()) {
	
		$sql = "SELECT code FROM patients WHERE ";
		
		$filters = 0;
		// filter by sex
		if ($sex = Arr::get($post, 'sex')) {
			$sql .= "AES_DECRYPT(sex,'".Encryption::get_key()."')='".$sex."' AND ";
			$filters++;
		}
		// filter by age
		if (($age_min = Arr::get($post, 'age_min')) && ($age_max = Arr::get($post, 'age_max'))) {
			$sql .= "AES_DECRYPT(age,'".Encryption::get_key()."') BETWEEN ".$age_min." AND ".$age_max." AND ";
			$filters++;
		}
		// chop off unwanted characters
		if($filters) {
			$sql = substr($sql, 0, -5);
		}
		else {
			$sql = substr($sql, 0, -7);
		}
		
		
		// run query and return results
		$result = DB::query(Database::SELECT, $sql)->execute();
		$patients = array();
		foreach($result->as_array() as $row) {
			$patients[] = ORM::factory('patient', $row['code']);
		}
		return $patients;
		
	}
	
	
	/*
	List patients with order by
	@param int limit
	@param int offset
	@param string orderby
	@return array patients
	*/
	public static function get_list($limit, $offset, $ord) {
		
			$patients = array();
			
			if($ord=='village_code') {
			
				$patients = array();
				$sql = "SELECT patients.code FROM patients
						INNER JOIN households ON patients.household_code=households.code
						ORDER BY AES_DECRYPT(village_code,'".Encryption::get_key()."') ASC LIMIT ".$offset.",".$limit;
				$result = DB::query(Database::SELECT, $sql)->execute();
				foreach($result->as_array() as $row) {
					$patients[] = ORM::factory('patient', $row['code']);
				}
				
			}
			else {
			
				$list = ORM::factory('patient');
				
				if(in_array($ord, $list->_encrypted))  {
					$list->order_by(DB::expr("AES_DECRYPT(".$ord.",'".Encryption::get_key()."')"), 'ASC');
				}
				else {
					$list->order_by($ord, 'ASC');
				}
				
				$patients = $list->limit($limit)
								->offset($offset)
								->find_all();
							
			}
			return $patients;
	}
	
	
	
	public static function get_count() {
		$count = ORM::factory('patient')
					->count_all();

		return $count;
	}
	
	
	/*
	reassign map coordinates to make them unique
	@param array 
	@return array
	*/
	public static function map_patients ($patients) {
		$mapped_patients = array();
		$increment = 0;
		$households = array();
		//echo Kohana::debug($patients);
		foreach($patients as $patient) {
			if(! isset($households[$patient->household_code])) {
				$households[$patient->household_code]=array();
			}
			array_push($households[$patient->household_code], $patient);
		}
		foreach($households as $household){
			$increment = 0;
			foreach($household as $patient) {
				$patient->map_lat = $patient->household->gps_lat+$increment;
				$patient->map_long = $patient->household->gps_long+$increment;
				$increment+=0.00001;
				$mapped_patients[] = $patient;
			}
		}
		return $mapped_patients;
	}
	
}