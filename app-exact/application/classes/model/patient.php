<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Patient extends ORM {
	
	protected $_primary_key = 'code';
	protected $_has_many = array (
								'form_datas'=>array('foreign_key'=>'patient_code')
							);
	
	
	
	
	
	
	
	public static function get_count() {
		$count = ORM::factory('patient')
					->count_all();

		return $count;
	}
	
	/*
 	 * Validation for editing form details
 	 */
	public function validate(& $array, $mode) 
	{
		// Initialise the validation library and use some rules
		$array = Validate::factory($array)
						->rule('phone_id', 'not_empty')
						->rules('email', array('not_empty'=>Null,'email'=>Null));
		
		if($mode=='create') {
			$array->rule('code', 'not_empty');
		}
 
		return $array;
	}
	
	
	/*
	List patients with order by
	@param int limit
	@param int offset
	@param string orderby
	@return array patients
	*/
	public static function get_list($limit, $offset, $ord) {
		
			$patients = ORM::factory('patient')
								->order_by('code', 'ASC')
								->limit($limit)
								->offset($offset)
								->find_all();
			
			return $patients;
	}
	
	
	public static function get_code_val_array() {
			$arr = array(''=>'');
			$patients = ORM::factory('patient')->find_all();
			foreach($patients as $patient) {
				$arr[$patient->code] = $patient->code;
			}
			return $arr;
		}
	
}