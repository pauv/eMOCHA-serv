<?php defined('SYSPATH') or die('No direct script access.');
 
 /*
 Models extra files connected to a specific form
 e.g. images
 */
class Model_Form_File extends ORM {

	// this relationship is really a 'has_one' relationship
	// but I use belongs_to because the key resides in the form_files table						
	protected $_belongs_to = array (
								'form' => array(
													'model' => 'form',
													'foreign_key' => 'form_id'
												),
								'file' => array(
													'model' => 'file',
													'foreign_key' => 'file_id'
												)
							);	
							
							
	 public function api_array () {
	 	
    	$arr = Array(
					"type"		=> $this->type, 
					"label"		=> $this->label, 
					"config"	=> $this->config,
					"file"		=> $this->file->api_array()
		  			);
		return $arr;
    }
	
	
	/*
 	 * Validation for editing form details
 	 */
	public function validate(& $array, $mode) 
	{
		// Initialise the validation library and use some rules
		$array = Validate::factory($array)
						->rules('type', array(
												'not_empty'=>NULL
												))
						->filter('config', 'trim')
						->filter('label', 'trim');
		
		if($mode=='create') {
			
			$array->rules('newfile', array(
										'Upload::valid' => array(),
										'upload::not_empty'=>NULL, 
								  		'Upload::size' => array('1M')
								  		));
		}
		else {
		
			$array->rules('newfile', array(
										'Upload::valid' => array(), 
								  		'Upload::size' => array('1M')
								  		));
		
		}
	
 
		return $array;
	}
	
	// override delete method to handle files
	public function delete($id = NULL) {
	
		if($this->file->loaded()){
			$this->file->delete();
		}
		parent::delete();
	}
}