<?php defined('SYSPATH') or die('No direct script access.');
  
class Controller_Admin extends Emocha_Controller_Admin {


	////////
	// Editing patients
	// for apps where the patient data is
	// entered manually
	///////
	
	
	public function action_patients($action=false) {
		$this->template->title = 'Studies/patients';
		$data['patients'] = ORM::factory('patient')->where('active','=',1)->find_all();
		$data['action'] = $action;
		$this->template->content = View::factory('admin/patients', $data);
	}
	
	
	public function action_edit_patient($id=false)
	{	
		$this->template->title = 'Edit patient';
		$this->template->curr_nav = 'patients';
		//Load the view
		$content = $this->template->content = View::factory('admin/edit_patient');
		if($id) {
			$patient = ORM::factory('patient')
						->where('id','=',$id)
						->find();
			$mode = 'edit';
		}
		else {
			$patient = ORM::factory('patient');
			$mode = 'create';
		}
		
		//echo Kohana::debug($patient->as_array());
 
		//If there is a post and $_POST is not empty
		if ($_POST)
		{
		
			$errors = array();
			
 			// xss clean post vars
 			$post = Arr::xss($_POST);
 			// return posted values to patient
 			// in case of error
			$content->form_vals = $post;
			if($mode=='edit'){
				$content->form_vals['code'] = $patient->code;
			}

 			
			//Load the validation rules, filters etc...
			$validation = $patient->validate($post, $mode);	
			
 
			//If the validation data validates using the rules setup in the user model
			if ( ! $validation->check())
			{
				$errors = $validation->errors('patient');
				
				// customise phone_id error
				// to show patient already using phone
				if(isset($errors['phone_id'])) {
					$phone_patient = ORM::factory('patient')
						->where('phone_id','=',$post['phone_id'])
						->and_where('active','=',1)
						->find();
					if($phone_patient->loaded()) {
						$errors['phone_id'] .= " (patient code: ".$phone_patient->code.")";
					}
				}
				
			}
			else 
			{
				$patient->values($validation);
			}
			//echo Kohana::debug($patient->as_array());
			
			/*
			 * Check for errors
			 */
			if (count($errors)) {
			
				$content->errors = $errors;
			
			}
			
			else 
			{
				$patient->save();
				Request::instance()->redirect('admin/patients/saved');
			}
			
	
		}
		
		else 
		{
			// assign current form data
			$content->form_vals = $patient->as_array();
			
			
		}
		
		
		// assign vars to patient
		$content->mode = $mode;
		$content->id = $id ? $id:'';
		$content->phones = Phone::get_id_val_array();
		
	}



	// confirm delete request
	public function action_delete_patient ($id=false) {
	
		$this->template->curr_nav = 'patients';
		if(!$id) {
			Request::instance()->redirect('admin/patients');
		}
		
		$content = $this->template->content = View::factory('admin/delete_patient_confirm');
		$content->patient = ORM::factory('patient')
						->where('id','=',$id)
						->find();

	}
	
	
	// confirmed: delete both file and db record
	public function action_delete_patient_confirmed ($id=false) {
	
		if(!$id) {
			redirect('admin/patients');
		}
		
		$patient = ORM::factory('patient')
						->where('id','=',$id)
						->find();
		$patient->active = 0;
		$patient->save();
		Request::instance()->redirect('admin/patients/deleted');
		
		
	}
	
	
}