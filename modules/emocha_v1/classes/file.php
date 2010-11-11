<?php


class File extends Kohana_File{

	/*
	public static function list_files_in_dir($source_dir, $ext = '', $depth = 0) {
		static $_filedata = array();
		echo $source_dir.'<br />';
		// Reset the static variable when this function is not being
		// called by itself. Before this fix $_filedata grew larger 
		// each time the function was called.
		if ($depth++ == 0) {
			$_filedata = array();
		}

		if ($fp = @opendir($source_dir)) {
			while (FALSE !== ($file = readdir($fp))) {
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0) {
					 Self::list_files_in_dir($source_dir.$file.DIRECTORY_SEPARATOR, $ext, $depth);
				} elseif (strncmp($file, '.', 1) !== 0) {
					if ($ext == '' || substr($file, strrpos($file, '.')) == ".$ext") {					
						$info = pathinfo($source_dir.$file);
						$info['relative_path'] = $source_dir;
						$_filedata[] = $info;
					} 
				}
			}
	
		} 
		return $_filedata;
	}
	*/



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