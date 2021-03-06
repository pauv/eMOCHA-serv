<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_Admin extends Controller_Site {

	// only users with admin role can access this page
	public $roles_required = array('admin');

	public function before()
	{
		parent::before();
		
		$this->template->title = 'Admin';
		$this->template->nav = View::factory('admin/nav');
		$this->template->curr_menu = 'admin';

	}
	
	
	public function action_index() {
		 Request::instance()->redirect('admin/new_users');
	}
	
	public function action_new_users($action_taken='') {
		
		$content = $this->template->content = View::factory('admin/new_users');	
		$content->users = Model_User::get_unconfirmed_users();
		$content->action_taken = $action_taken;
	
	}
	
	
	public function action_confirm_user($id) {
		
		if($id) {
			$user = ORM::factory('user', $id);
			$user->confirm();
			Request::instance()->redirect('admin/new_users/confirmed');
		}
		
	}
	
	
	public function action_delete_user($id) {
		
		if($id) {
			$user = ORM::factory('user', $id);
			$user->delete();
			Request::instance()->redirect('admin/new_users/deleted');
		}
		
	}
	
	public function action_delete_user_confirm($id) {
		
		$content = $this->template->content = View::factory('admin/delete_user_confirm');	
		$content->user = ORM::factory('user', $id);
		
	}
	
	
	public function action_forms($action=false) {
		$data['forms'] = ORM::factory('form')->find_all();
		$data['action'] = $action;
		$this->template->content = View::factory('admin/forms', $data);
	}
	
	
	public function action_edit_form($id=false)
	{	
		$this->template->curr_nav = 'forms';
		//Load the view
		$content = $this->template->content = View::factory('admin/edit_form');
		if($id) {
			$form = ORM::factory('form', $id);
			$mode = 'edit';
		}
		else {
			$form = ORM::factory('form');
			$mode = 'create';
		}
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
		
			$errors = array();
			
 			// xss clean post vars
 			$post = Arr::xss($_POST);
 			// return posted values to form
 			// in case of error
			$content->form_vals = $post;
			
			$vars = array_merge($post, $_FILES);
 			
 			//var_dump($vars);
 			//exit;
 			
			//Load the validation rules, filters etc...
			$validation = $form->validate($vars, $mode);	
			
 
			//If the validation data validates using the rules setup in the user model
			if ( ! $validation->check())
			{
				$errors = $validation->errors('form');
			}
			else 
			{
			
				$form->values($validation);
	
				
				// file
				if(Arr::get($_FILES['newfile'], 'name')) {
				
					// delete old form file
					if ($form->file->loaded()) $form->file->delete();
					
					$path_from_root = 'sdcard/emocha/odk/forms/';
					$target_path = DOCROOT.$path_from_root;
					$destination_file = upload::save($_FILES['newfile'], $_FILES['newfile']['name'], $target_path);
					// writing thumbnail failed, report error
					if( ! $destination_file) {
						$errors = array(Kohana::message('sdcard', 'upload_file'));
					}
					else {
						// save file db info
						$file = ORM::factory('file');
						$file->filename = basename($destination_file);
						$file->path = $path_from_root.$file->filename;
						$file->ts = filectime($destination_file);
						$file->size = filesize($destination_file);
						$file->md5 = md5_file($destination_file);
						$file->save();
						
						$form->file_id = $file->id;
					}
				}
			}
			
			
			/*
			 * Check for errors
			 */
			if (count($errors)) {
			
				$content->errors = $errors;
			
			}
			
			else 
			{
				$form->save();
				Request::instance()->redirect('admin/forms/saved');
				
			}
			
	
		}
		
		else 
		{
			// assign current form data
			$content->form_vals = $form->as_array();
			
		}
		
		
		// assign vars to form
		$content->mode = $mode;
		$content->id = $id ? $id:'';
		$content->file_path = $form->file->loaded() ? $form->file->path:'';
		// assign parent options
		$parents = Model_Form::get_id_val_array();
		$parents_empty_option = array(0=>'');
		$content->parents = $parents_empty_option+$parents;
		
	}



	// confirm delete request
	public function action_delete_form ($id=false) {
	
		$this->template->curr_nav = 'forms';
		if(!$id) {
			Request::instance()->redirect('admin/forms');
		}
		
		$content = $this->template->content = View::factory('admin/delete_form_confirm');
		$content->form = ORM::factory('form', $id);

	}
	
	
	// confirmed: delete both file and db record
	public function action_delete_form_confirmed ($id=false) {
	
		if(!$id) {
			redirect('edu/forms');
		}
		
		$form = ORM::factory('form', $id);
		// delete media (dependent files handled by delete method)
		$form->delete();
		Request::instance()->redirect('admin/forms/deleted');
		
		
	}
	
	
	// phones list
	public function action_phones($action=false) {
		$data['phones'] = ORM::factory('phone')->order_by('creation_ts', 'DESC')->find_all();
		$data['action'] = $action;
		$this->template->content = View::factory('admin/phones', $data);
	}
	
	
	
	public function action_edit_phone($id=false)
	{	
		$this->template->curr_nav = 'phones';
		//Load the view
		$content = $this->template->content = View::factory('admin/edit_phone');
		$phone = ORM::factory('phone', $id);
		$content->phone = $phone;
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
		
			$errors = array();
			
 			/*if(!$_POST['password']) {
 				$errors = array(Kohana::message('phone', 'enter_password'));
 			}*/
 			
			
			/*
			 * Check for errors
			 */
			if (count($errors)) {
			
				$content->errors = $errors;
			
			}
			
			else 
			{
				$phone->edit($_POST['validated'], $_POST['password'], $_POST['comments']);
				Request::instance()->redirect('admin/phones/saved');
				
			}
			
	
		}
		
	}
	
	
	// ALARMS
	
	public function action_alarms($action=false) {
		$content = $this->template->content = View::factory('admin/alarms');
		$content->alarms = ORM::factory('alarm')->find_all();
		$content->action = $action;
		
	}
	
	/*
	edit and save alarm
	*/
	public function action_alarm($id) {
		$this->template->curr_nav = 'alarms';
		$alarm = ORM::factory('alarm', $id);
		$content = $this->template->content = View::factory('admin/edit_alarm');
		$content->alarm = $alarm;
	
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
		
			$errors = array();
			
 			
			/*
			 * Check for errors
			 * TODO validation
			 */
			if (count($errors)) {
			
				$content->errors = $errors;
			
			}
			
			else 
			{
				foreach($_POST as $key=>$val) {
				
					if(stristr($key, 'condition')) {
						$parts = explode('_', $key);
						$id = $parts[1];
						$condition = ORM::factory('alarm_condition', $id);
						$condition->value = $val;
						$condition->save();
					}
					if(stristr($key, 'action')) {
						// this is a bit of a hack, should be reworked
						// if and when the action list expands
						if($key=='action_user_id') {
							$action = ORM::factory('alarm_action')->where('alarm_id','=',$alarm->id)->and_where('type','=','email')->find();
							$action->user_id = $val;
							$action->save();
						}
					}
				}
				Request::instance()->redirect('admin/alarms/saved');
			}
			
	
		}
		
	}
	
	
	

} 