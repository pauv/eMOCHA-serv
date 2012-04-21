<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Admin Controller
 *
 * @package    eMOCHA
 * @author     George Graham
 * @copyright  2010-2012 George Graham - gwgrahamx@gmail.com
 * @license    GNU General Public License - http://www.gnu.org/licenses/gpl.html
 */  
class Emocha_Controller_Admin extends Controller_Site {

	// only users with admin role can access this page
	public $roles_required = array('admin');

	/**
	 *  before()
	 *
	 * Run before any action
	 */
	public function before()
	{
		parent::before();
		
		$this->template->title = 'Admin';
		$this->template->nav = View::factory('admin/nav');
		$this->template->curr_menu = 'admin';

	}
	
	
	/**
	 *  index()
	 *
	 * Default action
	 */
	public function action_index() {
		 Request::instance()->redirect('admin/new_users');
	}
	
	
	/**
	 *  action_new_users()
	 *
	 * List new users pending confirmation
	 *
	 * @param string
	 */
	public function action_new_users($action_taken='') {
		
		$this->template->title = 'New users';
		$content = $this->template->content = View::factory('admin/new_users');	
		$content->users = Model_User::get_unconfirmed_users();
		$content->action_taken = $action_taken;
	
	}
	
	/**
	 *  action_confirm_user()
	 *
	 * Confirm user
	 *
	 * @param int
	 */
	public function action_confirm_user($id) {
		
		if($id) {
			$user = ORM::factory('user', $id);
			$user->confirm();
			Request::instance()->redirect('admin/new_users/confirmed');
		}
		
	}
	
	/**
	 *  action_delete_user()
	 *
	 * Delete user
	 *
	 * @param int
	 */
	public function action_delete_user($id) {
		
		if($id) {
			$user = ORM::factory('user', $id);
			$user->delete();
			Request::instance()->redirect('admin/new_users/deleted');
		}
		
	}
	
	/**
	 *  action_delete_user_confirm()
	 *
	 * Confirm deletion of user
	 *
	 * @param int
	 */
	public function action_delete_user_confirm($id) {
		
		$this->template->title = 'Delete user';
		$content = $this->template->content = View::factory('admin/delete_user_confirm');	
		$content->user = ORM::factory('user', $id);
		
	}
	
	/**
	 *  action_forms()
	 *
	 * List forms
	 *
	 * @param string
	 */
	public function action_forms($action=false) {
		$this->template->title = 'Forms';
		$data['forms'] = ORM::factory('form')->find_all();
		$data['action'] = $action;
		$this->template->content = View::factory('admin/forms', $data);
	}
	
	/**
	 *  action_edit_form()
	 *
	 * Edit form details
	 *
	 * @param int
	 */
	public function action_edit_form($id=false)
	{	
		$this->template->title = 'Edit form';
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
		
	}



	/**
	 *  action_delete_form()
	 *
	 * Delete form
	 *
	 * @param int
	 */
	public function action_delete_form ($id=false) {
	
		$this->template->title = 'Delete form';
		$this->template->curr_nav = 'forms';
		if(!$id) {
			Request::instance()->redirect('admin/forms');
		}
		
		$content = $this->template->content = View::factory('admin/delete_form_confirm');
		$content->form = ORM::factory('form', $id);

	}
	
	
	/**
	 *  action_delete_form_confirmed()
	 *
	 * Confirm deletion of form
	 *
	 * @param int
	 */
	public function action_delete_form_confirmed ($id=false) {
	
		if(!$id) {
			redirect('admin/forms');
		}
		
		$form = ORM::factory('form', $id);
		// delete media (dependent files handled by delete method)
		$form->delete();
		Request::instance()->redirect('admin/forms/deleted');
		
		
	}
	
	/**
	 *  action_form_files()
	 *
	 * List files for a form
	 *
	 * @param int
	 * @param string
	 */
	public function action_form_files($id, $action=false)
	{	
		$this->template->title = 'Form files';
		$this->template->curr_nav = 'forms';
		//Load the view
		$content = $this->template->content = View::factory('admin/form_files');
		$content->form = ORM::factory('form', $id);
		$content->action = $action;
	}
	
	
	/**
	 *  action_edit_form_file()
	 *
	 * Edit file for a form
	 *
	 * @param int
	 * @param int
	 */
	public function action_edit_form_file($form_id, $id=false)
	{	
		$this->template->title = 'Edit form file';
		$this->template->curr_nav = 'form_files';
		$content = $this->template->content = View::factory('admin/edit_form_file');
		
		$form = ORM::factory('form', $form_id);
		
		if($id) {
			$form_file = ORM::factory('form_file', $id);
			$mode = 'edit';
		}
		else {
			$form_file = ORM::factory('form_file');
			$mode = 'create';
		}
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
		
			$errors = array();
			
 			// xss clean post vars
 			$post = Arr::xss($_POST);
 			// return posted values to form_file
 			// in case of error
			$content->form_vals = $post;
			
			$vars = array_merge($post, $_FILES);

 			
			//Load the validation rules, filters etc...
			$validation = $form_file->validate($vars, $mode);	
			
 
			//If the validation data validates using the rules setup in the user model
			if ( ! $validation->check())
			{
				$errors = $validation->errors('form_file');
			}
			else 
			{
			
				$form_file->values($validation);
	
				
				// file
				if(Arr::get($_FILES['newfile'], 'name')) {
				
					// delete old form_file file
					if ($form_file->file->loaded()) $form_file->file->delete();
					
					$path_from_root = 'sdcard/emocha/odk/form_files/';
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
						
						$form_file->file_id = $file->id;
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
				$form_file->form_id = $form->id;
				$form_file->save();
				Request::instance()->redirect('admin/form_files/'.$form_id.'/saved');
				
			}
			
	
		}
		
		else 
		{
			// assign current form data
			$content->form_vals = $form_file->as_array();
			
		}
		
		
		// assign vars to form
		$content->mode = $mode;
		$content->form_id = $form_id;
		$content->id = $id ? $id:'';
		$content->file_path = $form_file->file->loaded() ? $form_file->file->path:'';
		
	}
	
	
	/**
	 *  action_delete_form_file()
	 *
	 * Delete file for a form
	 *
	 * @param int
	 * @param int
	 */
	public function action_delete_form_file ($form_id, $id=false) {
	
		$this->template->title = 'Delete form file';
		$this->template->curr_nav = 'forms';
		if(!$id) {
			Request::instance()->redirect('admin/form_files');
		}
		
		$content = $this->template->content = View::factory('admin/delete_form_file_confirm');
		$content->form_file = ORM::factory('form_file', $id);

	}
	
	
	/**
	 *  action_delete_form_file_confirmed()
	 *
	 * Confirm deletion of file for a form
	 *
	 * @param int
	 * @param int
	 */
	public function action_delete_form_file_confirmed ($form_id, $id=false) {
	
		if(!$id) {
			redirect('admin/form_files');
		}
		
		$form_file = ORM::factory('form_file', $id);
		// delete media (dependent files handled by delete method)
		$form_file->delete();
		Request::instance()->redirect('admin/form_files/'.$form_id.'/deleted');
		
		
	}
	
	
	
	/**
	 *  action_phones()
	 *
	 * List phones
	 *
	 * @param string
	 */
	public function action_phones($action=false) {
	
		$this->template->title = 'Phones';
		$data['phones'] = ORM::factory('phone')->order_by('creation_ts', 'DESC')->find_all();
		$data['action'] = $action;
		$this->template->content = View::factory('admin/phones', $data);
	}
	
	
	/**
	 *  action_edit_phone()
	 *
	 * Edit phone details
	 *
	 * @param int
	 */
	public function action_edit_phone($id=false)
	{	
		$this->template->title = 'Edit phone';
		$this->template->curr_nav = 'phones';
		//Load the view
		$content = $this->template->content = View::factory('admin/edit_phone');
		if($id) {
			$phone = ORM::factory('phone', $id);
			$mode = 'edit';
		}
		else {
			$phone = ORM::factory('phone');
			$mode = 'create';
		}
		$content->phone = $phone;
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
		
			$errors = array();
			// xss clean post vars
 			$post = Arr::xss($_POST);
			$content->form_vals = $post;
			
			
			/*
			 * Check for errors
			 */
			if (count($errors)) {
			
				$content->errors = $errors;
			
			}
			
			else 
			{
				$phone->edit($_POST['imei'], $_POST['validated'], $_POST['password'], $_POST['comments'], $_POST['c2dm_disable']);
				Request::instance()->redirect('admin/phones/saved');
				
			}
			
	
		}
		else 
		{
			// assign current form data
			$content->form_vals = $phone->as_array();
			
		}
	}
	
	
	
	/**
	 *  action_delete_phone()
	 *
	 * Delete phone
	 *
	 * @param int
	 */
	public function action_delete_phone ($id=false) {
	
		$this->template->title = 'Delete phone';
		$this->template->curr_nav = 'phones';
		if(!$id) {
			Request::instance()->redirect('admin/phones');
		}
		
		$content = $this->template->content = View::factory('admin/delete_phone_confirm');
		$content->phone = ORM::factory('phone', $id);

	}
	
	
	/**
	 *  action_delete_phone_confirmed()
	 *
	 * Confirm deletion of phone
	 *
	 * @param int
	 */
	public function action_delete_phone_confirmed ($id=false) {
	
		if(!$id) {
			redirect('admin/phones');
		}
		
		$phone = ORM::factory('phone', $id);
		
		$phone->delete();
		Request::instance()->redirect('admin/phones/deleted');
		
		
	}
	
	
	/**
	 *  action_alarms()
	 *
	 * List alarms
	 *
	 * @param string
	 */
	public function action_alarms($action=false) {
		$this->template->title = 'Alarms';
		$content = $this->template->content = View::factory('admin/alarms');
		$content->alarms = ORM::factory('alarm')->find_all();
		$content->action = $action;
		
	}
	
	/**
	 *  action_alarm()
	 *
	 * Edit alarm details
	 *
	 * @param int
	 */
	public function action_alarm($id) {
		$this->template->curr_nav = 'alarms';
		$alarm = ORM::factory('alarm', $id);
		$content = $this->template->content = View::factory('admin/edit_alarm');
		$content->alarm = $alarm;
		$this->template->title = 'Edit alarm: '.$alarm->name;
	
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
//////////////////////// configs //////////////////////////	
	/**
	 *  action_choose_config_type()
	 *
	 * Choose config type to be edited
	 *
	 */
	public function action_choose_config_type()
	{
		$this->template->title = 'Choose config type';
		$content = $this->template->content = View::factory('admin/choose_config_type');
	}
	
	
  /**
   *  action_configs()
   *
   * List configs by type
   *
   * @param string	type: config type
   * @param string	action: 
   */
  public function action_configs($type=false, $action=false) {
    $this->template->title = 'Configs: '.$type.' values';
    $content = $this->template->content = View::factory('admin/configs');
    $content->configs = ORM::factory('config')->where('type','=',$type)->find_all();

		//set values to the view
    $content->action = $action;
		$content->type = $type;
  }


	/**
	 *  action_edit_config()
	 *
	 * Edit config details
	 *
   * @param string	type: config type
	 * @param int
	 */
	public function action_edit_config($type=false, $id=false)
	{	
		$this->template->title = 'Edit config';
		$this->template->curr_nav = 'configs';
		//Load the view
		$content = $this->template->content = View::factory('admin/edit_config');
		if($id) {
			$config = ORM::factory('config', $id);
			$mode = 'edit';
		}
		else {
			$config = ORM::factory('config');
			$mode = 'create';
		}
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
			$errors = array();
			
 			// xss clean post vars
 			$post = Arr::xss($_POST);
 			// return posted values to config in case of error
			$content->form_vals = $post;
			
			$vars = array_merge($post, $_FILES);
 			
			//Load the validation rules, filters etc...
			$validation = $config->validate($vars, $mode);	
 
			//If the validation data validates using the rules setup in the user model
			if ( ! $validation->check())
			{
				$errors = $validation->errors('config');
			}
			else 
			{
				// time zone extra validation: not sure this the rightest way (static method?)
				if($config->type == Kohana::config('values.platform') AND $config->label == Kohana::config('values.app_time_zone'))
				{
					$validation->rule('content','Model_Config::validate_time_zone');
					if ( ! $validation->check()) 
					{
						$errors = array(Kohana::message('admin', 'invalid_time_zone'));
					}
					else 
					{
						$config->values($validation);
					}
				} else 
				{
					$config->values($validation);
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
				$config->type = $type; 
				$config->save();
				Request::instance()->redirect('admin/configs/'.$type.'/saved');
				//Request::instance()->redirect('admin/configs/saved');
			}
		}
		else 
		{
			// assign current config data
			$content->form_vals = $config->as_array();
			
		}
		
		// assign vars to config
		$content->mode = $mode;
		$content->id = $id ? $id:'';
		$content->type = $type;
	}

	
	/**
	 *  action_delete_config()
	 *
	 * Delete config variable
	 *
   * @param string	type: config type
	 * @param int
	 */
	public function action_delete_config ($type=false, $id=false) {
	
		$this->template->title = 'Delete '.$type.' config';
		$this->template->curr_nav = 'configs';
		if(!$id) {
			Request::instance()->redirect('admin/configs/'.$type);
		}
		
		$content = $this->template->content = View::factory('admin/delete_config_confirm');
		$content->config = ORM::factory('config', $id);
	}

	/**
	 *  action_delete_config_confirmed()
	 *
	 * Confirm deletion of config
	 *
   * @param string	type: config type
	 * @param int
	 */
	public function action_delete_config_confirmed ($type=false, $id=false) {
	
		if(!$id) {
			redirect('admin/configs/'.$type);
		}
		
		$config = ORM::factory('config', $id);
		$config->delete();
		Request::instance()->redirect('admin/configs/'.$type.'/deleted');
	}
} 
