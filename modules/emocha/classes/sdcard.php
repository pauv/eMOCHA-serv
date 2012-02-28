<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sdcard helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Sdcard {
   	
   	
   
	/**
	 * get_file_list_from_db_by_type()
	 *
	 * Get files by type  - forms/library/courses etc
	 * 
	 * @param string
	 * @return array
	 */
	public static function get_file_list_from_db_by_type($type) {
	
		$files = ORM::factory('file')
						->where('type','=', $type)
						->find_all();
		
		return $files;
		
	}
	
	
	/**
	 * get_file_list_from_db_all()
	 *
	 * Get all files
	 * 
	 * @return array
	 */
	public static function get_file_list_from_db_all() {
	
		$files = ORM::factory('file')
						->find_all();
						
		return $files;
		
	}
	
	
	/**
	 * get_file_list_from_ftp_upload()
	 *
	 * Get files in ~/sdcard/upload folder
	 * 
	 * @param string
	 * @return array
	 */
	public static function get_file_list_from_ftp_upload($ext) {
		$directory = "sdcard/upload/";
   		$files = glob($directory . "*.".$ext);
   		return $files;
   	}
   	
   	/**
	 * get_last_server_update()
	 *
	 * Get timestamp files last updated
	 * 
	 * @return int
	 */
   	public static function get_last_server_update() {
   		$result = DB::select('ts')
   						->from('files')
   						->order_by('ts', 'desc')
   						->execute();
   		if($result->count()) {
   			$row = $result->current();
   			return $row['ts'];
   		}
   		else {
   			return 0;
   		}

   	}
   	
   	/*
    TODO	DEPRECATE
    */
   	public static function get_file_list_from_db() {
   	
	  	$files = ORM::factory('file')->find_all();

		$files_arr = array();
		
		foreach ($files as $file) {
			$files_arr[] = Array(
		  			"path"		=> $file->path, 
		  			"ts"		=> $file->ts, 
		  			"size"		=> $file->size, 
		  			"md5"		=> $file->md5
		  		);
		}
		
	  	return $files_arr;   		
   	}

   	

	/**
	 * get_unique_file_name()
	 *
	 * get unique filename
	 * recursively checks for a valid unique name
	 * 
	 * @param string
	 * @param string
	 * @return string
	 */
	public static function get_unique_file_name($folder, $name) {
	
		if(is_file($folder.$name)) {
			
			$parts = explode('.', $name);
			$basename = $parts[0];
			$extension = array_pop($parts);
			$new_name = $basename.'_1.'.$extension;
			return Sdcard::get_unique_file_name($folder, $new_name);
				
		}
		return $name;
	}
   	
    
}