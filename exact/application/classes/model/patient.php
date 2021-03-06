<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Patient extends ORM {
	
	protected $_primary_key = 'code';
	protected $_has_many = array (
								'form_datas'=>array('foreign_key'=>'patient_code')
							);
	protected $_belongs_to = array('phone' => array('model' => 'phone', 'foreign_key' => 'phone_id'));
	
	
	
	
	
	
	
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
						->rules('email', array('not_empty'=>Null,'email'=>Null))
						->callback('phone_id', array($this, 'imei_available'));
		
		if($mode=='create') {
			$array->rule('code', 'not_empty');
			$array->callback('code', array($this, 'code_unique'));
		}
 
		return $array;
	}
	
	
	/**
	 * Check if code is not already taken
	 *
	 * @param    Validate  $array   validate object
	 * @param    string    $field   field name
	 */
	public function code_unique(Validate $array, $field)
	{
		$exists = (bool) DB::select(array('COUNT("*")', 'total_count'))
						->from($this->_table_name)
						->where('code',   '=',   $array[$field])
						->and_where('id',     '!=',   $this->id)
						->execute($this->_db)
						->get('total_count');
 
		if ($exists)
			$array->error($field, 'code_unique', array($array[$field]));
	}
	
	
	/**
	 * Check if imei is not already taken by an active patient
	 *
	 * @param    Validate  $array   validate object
	 * @param    string    $field   field name
	 */
	public function imei_available(Validate $array, $field)
	{
		$exists = (bool) DB::select(array('COUNT("*")', 'total_count'))
						->from($this->_table_name)
						->where('phone_id',   '=',   $array[$field])
						->and_where('id',     '!=',   $this->id)
						->and_where('active',     '=',   1)
						->execute($this->_db)
						->get('total_count');
 
		if ($exists)
			$array->error($field, 'imei_available', array($array[$field]));
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
			$patients = ORM::factory('patient')->where('active','=',1)->find_all();
			foreach($patients as $patient) {
				$arr[$patient->code] = $patient->code;
			}
			return $arr;
		}
	
	
 	/*
	get array of dates where patient has submitted form data
	@return array
	*/
	public function get_form_data_dates() {
		$dates = array();
			
		$sql = "SELECT DISTINCT(DATE(last_modified)) as mydate FROM uploaded_data
				WHERE patient_code='".$this->code."'
				ORDER BY mydate ASC";
		$result = DB::query(Database::SELECT, $sql)->execute();
		foreach($result->as_array() as $row) {
			$dates[] =$row['mydate'];
		}
		
		return $dates;

	}
	
	/*
	get form data for a given date
	@return array
	*/
	public function get_form_data_by_date($date) {
		$datas = ORM::factory('form_data')
				->where('patient_code','=',$this->code)
				->and_where(DB::expr("DATE(last_modified)"),'=',$date)
				->order_by('last_modified')
				->find_all();
		
		return $datas;
	}
}