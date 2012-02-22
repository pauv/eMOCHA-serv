<?php defined('SYSPATH') or die('No direct script access.');
/**
 * File Model
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - george@ccghe.net
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Model_File extends ORM {


	// override delete method to handle files
	public function delete($id = NULL) {
	
		$full_path = DOCROOT.$this->path;
		if(is_file($full_path)){
			unlink($full_path);
		}
		
		parent::delete();
	}
	
	
	 public function api_array () {
    	$arr = Array(
    				"id"		=> $this->id,
					"path"		=> $this->path, 
					"ts"		=> $this->ts, 
					"size"		=> $this->size, 
					"md5"		=> $this->md5
		  			);
		return $arr;
    }
	

}