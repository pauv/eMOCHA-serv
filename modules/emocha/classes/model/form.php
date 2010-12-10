<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_Form extends ORM {

	/*
	ORM vars
	*/
	protected $_sorting = array('id'=>'ASC');

	/*
	ORM Relationships
	*/
	protected $_has_many = array (
								'form_datas'=>array(),
								'form_files'=>array()
							);
							
	// this relationship is really a 'has_one' relationship
	// but I use belongs_to because the key resides in the forms table						
	protected $_belongs_to = array (
								'file' => array(
													'model' => 'file',
													'foreign_key' => 'file_id'
												)
							);			
	// load these relationships by default
	protected $_load_with = array ('file');										
	
	
	/*
 	 * return list of forms as id=>val
 	 * (useful for dropdowns)
 	 */
	
	public static function get_id_val_array() {
		$arr = array();
		$forms = ORM::factory('form')->find_all();
		foreach($forms as $form) {
			$arr[$form->id] = $form->name;
		}
		return $arr;
	}
	
	
	/*
 	 * get name with suffix
 	 */
	
	public function get_short_name() {
		$arr = explode('.',$this->name);
		if(isset($arr[0])) {
			return $arr[0];
		}
		return '';
	}
	
	
	/*
 	 * Validation for editing form details
 	 */
	public function validate(& $array, $mode) 
	{
		// Initialise the validation library and use some rules
		$array = Validate::factory($array)
						->rules('name', array(
												'not_empty'=>NULL
												))
						->rules('description', array(
												'not_empty'=>NULL
												))
						->rules('group', array(
												'not_empty'=>NULL
												))
						->rules('code', array(
												'not_empty'=>NULL
												))
						->callback('code', array($this, 'code_unique'))
						->filter('name', 'trim')
						->filter('description', 'trim')
						->filter('conditions', 'trim')
						->filter('label', 'trim');
		
		if($mode=='create') {
			
			$array->rules('newfile', array(
										'Upload::valid' => array(),
										'upload::not_empty'=>NULL,
								  		'Upload::type' =>array('Upload::type' => array('xml')), 
								  		'Upload::size' => array('1M')
								  		));
		}
		else {
		
			$array->rules('newfile', array(
										'Upload::valid' => array(),
								  		'Upload::type' =>array('Upload::type' => array('xml')), 
								  		'Upload::size' => array('1M')
								  		));
		
		}
	
 
		return $array;
	}
	
	/**
	 * Code validation callback
	 * @param    Validate  $array   validate object
	 * @param    string    $field   field name
	 * @param    array     $errors  current validation errors
	 * @return   array
	 */
	public function code_unique(Validate $array, $field)
	{
		// check the database for existing records
		   $code_exists = (bool) ORM::factory('form')
		   						->where('code', '=', $array[$field])
		   						->and_where('id', '!=', $this->id)
		   						->count_all();
		 
		   if ($code_exists)
		   {
			   // add error to validation object
			   $array->error($field, 'code_unique');
		   }
	}
	
	
	// override delete method to handle files
	public function delete($id = NULL) {
	
		if($this->file->loaded()){
			$this->file->delete();
		}
		parent::delete();
	}
	
	
	
		
		/*
		 * read from data a single instance result file
		 * into one row
		 */
		private function _get_row($row_num, $obj, $parent_id) {
			foreach($obj as $key => $val) {
				$id = ($parent_id ? "$parent_id." : '').$key; 
				if (count($val) > 0) {
					$this->_get_row($row_num, $val, $id);
				} else {
					$this->rows[$row_num][$id] = $val;
				}
			}
	
		}
		
		
		public function get_config() {
			$config = array();
			$config['id'] = $this->id;
			$config['name'] = $this->name;
			$config['group'] = $this->group;
			$config['code'] = $this->code;
			$config['description'] = $this->description;
			$config['conditions'] = json_decode($this->conditions);
			$config['label'] = $this->label;
			$config['template'] = $this->file->api_array();
			$config['files'] = array();
			/*echo "<hr>";
			echo $this->name."<br>";
			echo Kohana::debug($this->form_files->find_all());*/
			foreach ($this->form_files->find_all() as $form_file) {
				if ($form_file->loaded()) {
					$config['files'][] = $form_file->api_array();
				}
			}
			
			return $config;
		}
}