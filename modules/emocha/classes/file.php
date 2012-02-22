<?php defined('SYSPATH') or die('No direct script access.');
/**
 * File helper
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - george@ccghe.net
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class File extends Kohana_File{



	public static function list_files_in_ftp_upload($exts) {
		$directory = "sdcard/upload/";
		$files = array();
		foreach($exts as $ext) {
   			$ext_files = glob($directory . "*.".$ext);
   			if(is_array($ext_files)) {
   				$files = array_merge($files, $ext_files);
   			}
   		}
   		return $files;
   	}
   	
   	
   	/* get unique filename
	 * recursively checks for a valid unique name
	 * return string
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