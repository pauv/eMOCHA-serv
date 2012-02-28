<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form File Model (models extra files connected to a specific form)
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
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
							
	/**
	 * api_array()
	 * 
	 * Get array of variables for api return
	 * 
	 * @return array
	 */					
	 public function api_array () {
	 	
    	$arr = Array(
					"type"		=> $this->type, 
					"label"		=> $this->label, 
					"config"	=> $this->config,
					"file"		=> $this->file->api_array()
		  			);
		return $arr;
    }
	
	
	/**
	 * validate()
	 * 
	 * Validate edit details
	 *
	 * @param array
	 * @param string
	 * @return array
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
	
	/**
	 * delete()
	 * 
	 * Override delete method to handle files on disk
	 */
	public function delete($id = NULL) {
	
		if($this->file->loaded()){
			$this->file->delete();
		}
		parent::delete();
	}
}