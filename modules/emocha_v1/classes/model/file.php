<?php defined('SYSPATH') or die('No direct script access.');
 
class Model_File extends ORM {


	// override delete method to handle files
	public function delete($id = NULL) {
	
		$full_path = DOCROOT.$this->path;
		if(is_file($full_path)){
			unlink($full_path);
		}
		
		parent::delete();
	}
	

}