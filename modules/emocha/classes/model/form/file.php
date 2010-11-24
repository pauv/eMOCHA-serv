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
	
	
}