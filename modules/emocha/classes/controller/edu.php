<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Edu extends Controller_Site {


	public function before()
	{
		parent::before();
		
		$this->template->title = 'eMocha - Education';
		$this->template->nav = View::factory('edu/nav');
		$this->template->curr_menu = 'edu';

	}
	
	public function action_index()
	{
		Request::instance()->redirect('edu/courses');
	}
	
	
	public function action_courses($action=false) {
		$data['medias'] = ORM::factory('media')->where('type', '=', 'courses')->find_all();
		$data['section'] = 'courses';
		$this->template->title = 'eMocha - Courses';
		$data['action'] = $action;
		$this->template->content = View::factory('edu/file_list', $data);
	}
	
	public function action_lectures($action=false) {
		$data['medias'] = ORM::factory('media')->where('type', '=', 'lectures')->find_all();
		$data['section'] = 'lectures';
		$data['action'] = $action;
		$this->template->title = 'eMocha - Lectures';
		$this->template->content = View::factory('edu/file_list', $data);
	}
	
	public function action_library($action=false) {
		$data['medias'] = ORM::factory('media')->where('type', '=', 'library')->find_all();
		$data['section'] = 'library';
		$data['action'] = $action;
		$this->template->title = 'eMocha - Library';
		$this->template->content = View::factory('edu/file_list', $data);
	}
	
	
	function action_form($section=false, $errors=array()) {
		
		if(! $section) {
			Request::instance()->redirect('edu/index');
		}
		
		$this->template->curr_nav = $section;
		
		$media = ORM::factory('media');
		$media->type = $section;
		
		$data['allowed_file_types'] = $media->get_allowed_file_type();
		$data['thumbnail_allowed'] = $media->get_thumbnail_allowed();
		$data['section'] = $section;
		$data['errors'] = $errors;
		$data['ftp_files'] = File::list_files_in_ftp_upload($data['allowed_file_types']);
		
		$this->template->title = 'eMocha - '.ucfirst($section);
		$this->template->content = View::factory('edu/upload_form', $data);
		
		
	}
	
	
	
	function action_upload($section=false) {
	
		if(! $section) {
			Request::instance()->redirect('edu/index');
		}
		
		$this->template->curr_nav = $section;
		
		$errors = array();
		
		$path_from_root = 'sdcard/emocha/training/'.$section.'/';
		$target_path = DOCROOT.$path_from_root;
		
		$media = ORM::factory('media');
		$media->type = $section;
		
		
		###############################
		## fiel selected from ftp folder
		###############################
		
		if($ftp_file = Arr::get($_POST, 'ftp_file')) {

			$source_file = DOCROOT.'sdcard/upload/'.$ftp_file;
			$destination_file_name = File::get_unique_file_name($target_path, $ftp_file);
			$destination_file = $target_path.$destination_file_name;
				
			if (is_file($source_file)){
				copy($source_file, $destination_file);
			}
			else {
				$errors = array(Kohana::message('sdcard', 'ftp_file'));
			}
		
		}
	
		###############################
		## file uploaded via http
		###############################
		
		else
		{
		
			$validation = Validate::factory($_FILES)
						->rules('userfile', array(
											'upload::valid'=>NULL, 
											'upload::not_empty'=>NULL,
											'upload::type'=>array($media->get_allowed_file_type()), 
											'upload::size'=>array('2M')
											));
 			
			if ($validation->check())
			{
				$destination_file_name = File::get_unique_file_name($target_path, $_FILES['userfile']['name']);
				$destination_file = upload::save($_FILES['userfile'], $destination_file_name, $target_path);
				
				// writing file failed
				if( ! $destination_file) {
					$errors = array(Kohana::message('sdcard', 'upload_file'));
				}
				// writing file successful
				// check for thumbnail
				else {
					if(isset($_FILES['thumbnail']) && Arr::get($_FILES['thumbnail'], 'name')) {
				
						// thumbnail exists
						$file_name = basename($destination_file);
						$name_part = substr($file_name, 0, strrpos($file_name, '.'));
						$thumb_name = $name_part.'.jpg';
						
						// save the file
						$destination_thumb_file = upload::save($_FILES['thumbnail'], $thumb_name, $target_path);
						
						// writing thumbnail failed, roll back
						if( ! $destination_thumb_file) {
							unlink($destination_file);
							$errors = array(Kohana::message('sdcard', 'upload_file'));
						}
						
					}
				}
			
			}
			else {
			
				// file validation failed
				$errors = $validation->errors('sdcard');

				
			}
			
		}
		
		###############################
		## thumbnail
		###############################
		
		if(count($errors)==0 && isset($_FILES['thumbnail']) && Arr::get($_FILES['thumbnail'], 'name')) {
		
			// thumbnail exists
			
			$validation = Validate::factory($_FILES)
						->rules('thumbnail', array(
												'upload::valid'=>NULL, 
												'upload::type'=>array(array('jpg')), 
												'upload::size'=>array('1M')
												));
			if ($validation->check())
			{
				$file_name = basename($destination_file);
				$name_part = substr($file_name, 0, strrpos($file_name, '.'));
				$thumb_name = $name_part.'.jpg';
				
				// save the file
				$destination_thumb_file = upload::save($_FILES['thumbnail'], $thumb_name, $target_path);
				
				// writing thumbnail failed, roll back
				if( ! $destination_thumb_file) {
					unlink($destination_file);
					$errors = array(Kohana::message('sdcard', 'upload_file'));
				}
				
			}

				
			else {
				// thumbnail validation failed, roll back
				unlink($destination_file);
				$errors = $validation->errors('sdcard');
			}
			
		}
		
		###############################
		## check for errors
		###############################
		
		if (count($errors)) {
			return $this->action_form($section, $errors);
		}
		
		
		###############################
		## clean up and save to database
		###############################
		
		else
		{
		
			// remove ftp file
			if (isset($source_file)){
				if(is_file($source_file)) unlink($source_file);
			}
			
		
			// add data to database
			$media = ORM::factory('media');
			$media->type = $section;
			$media->title = Arr::get($_POST, 'title');
			
			// file
			$file = ORM::factory('file');
			$file->filename = basename($destination_file);
			$file->path = $path_from_root.$file->filename;
			$file->ts = filectime($destination_file);
			$file->size = filesize($destination_file);
			$file->md5 = md5_file($destination_file);
			$file->save();
			
			$media->file_id = $file->id;
			
			// thumbnail
			if(isset($destination_thumb_file)) {
			
				$thumb_file = ORM::factory('file');
				$thumb_file->filename = basename($destination_thumb_file);
				$thumb_file->path = $path_from_root.$thumb_file->filename;
				$thumb_file->ts = filectime($destination_thumb_file);
				$thumb_file->size = filesize($destination_thumb_file);
				$thumb_file->md5 = md5_file($destination_thumb_file);
				$thumb_file->save();
		
				$media->thumbnail_file_id = $thumb_file->id;
				
			}
			
			$media->save();
			
			//exit;
			// redirect to list page
	       	Request::instance()->redirect('edu/'.$section.'/uploaded');
		}
	}
	
	
	
	// confirm delete request
	public function action_delete ($section=false, $id=false) {
	
		if(! $section || !$id) {
			Request::instance()->redirect('edu/index');
		}
		
		$this->template->curr_nav = $section;
		
		$content = $this->template->content = View::factory('edu/delete_confirm');
		$content->section = $section;
		$content->media = ORM::factory('media', $id);

	}
	
	
	// confirmed: delete both file and db record
	public function action_delete_confirmed ($section=false, $id=false) {
	
		if(! $section || !$id) {
			redirect('edu/index');
		}
		
		$media = ORM::factory('media', $id);
		// delete media (dependent files handled by delete method)
		$media->delete();
		Request::instance()->redirect('edu/'.$section.'/deleted');
		
		
	}
	
	
}